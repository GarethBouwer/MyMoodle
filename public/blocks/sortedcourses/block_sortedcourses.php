<?php
defined('MOODLE_INTERNAL') || die();

class block_sortedcourses extends block_base {

    public function init() {
        $this->title = get_string('sortedcourses', 'block_sortedcourses');
    }

    public function applicable_formats() {
    // Allow on all pages – you can still choose
    // where to place it via the UI.
    return [
        'all' => true
    ];
}


    public function instance_allow_multiple() {
        // Only one instance per Dashboard.
        return false;
    }

    public function get_content() {
        global $USER, $CFG, $OUTPUT;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->text = '';
        $this->content->footer = '';

        require_once($CFG->libdir . '/enrollib.php');

        $fields = 'id, fullname, shortname, idnumber, category, visible';
        $courses = enrol_get_users_courses($USER->id, true, $fields, 'fullname ASC');

        if (empty($courses)) {
            $this->content->text = html_writer::tag('p',
                get_string('nocourses', 'block_sortedcourses')
            );
            return $this->content;
        }

        $sortmode = get_config('block_sortedcourses', 'sortmode');
        if ($sortmode !== 'category') {
            $sortmode = 'idnumber';
        }

        $courselist = array_values($courses);

        if ($sortmode === 'idnumber') {
            usort($courselist, function($a, $b) {
                $aid = $a->idnumber ?? '';
                $bid = $b->idnumber ?? '';

                if ($aid === '' && $bid === '') {
                    return strcmp($a->shortname, $b->shortname);
                }
                if ($aid === '') {
                    return 1;
                }
                if ($bid === '') {
                    return -1;
                }

                $cmp = strcmp($aid, $bid);
                if ($cmp === 0) {
                    return strcmp($a->shortname, $b->shortname);
                }
                return $cmp;
            });
        } else {
            usort($courselist, function($a, $b) {
                if ($a->category == $b->category) {
                    return strcmp($a->shortname, $b->shortname);
                }
                return $a->category <=> $b->category;
            });
        }

        $items = [];
        foreach ($courselist as $course) {
            if (empty($course->visible)) {
                continue;
            }

            $courseurl = new moodle_url('/course/view.php', ['id' => $course->id]);
            $label = $course->fullname;

            if (!empty($course->idnumber)) {
                $label = '[' . $course->idnumber . '] ' . $label;
            } else {
                $label = '[' . $course->shortname . '] ' . $label;
            }

            $items[] = html_writer::tag('li',
                html_writer::link($courseurl, $label)
            );
        }

        if (empty($items)) {
            $this->content->text = html_writer::tag('p',
                get_string('nocourses', 'block_sortedcourses')
            );
        } else {
            $this->content->text = html_writer::tag(
                'ul',
                implode('', $items),
                ['class' => 'list-unstyled']
            );
        }

        return $this->content;
    }
}
