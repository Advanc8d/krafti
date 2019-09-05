<?php

namespace App\Processors\User;

use App\Model\Course;
use App\Model\Lesson;

class Progress extends \App\Processor
{
    protected $scope = 'profile';


    public function post()
    {

        /** @var Lesson $lesson */
        if (!$lesson = Lesson::query()->find((int)$this->getProperty('lesson_id'))) {
            return $this->failure('Не могу загрузить урок');
        }
        /** @var Course $course */
        $course = $lesson->course;
        if (!$course->wasBought($this->container->user->id)) {
            return $this->failure('Вы забыли оплатить этот курс');
        }

        $progress = $this->container->user->getProgress($course);
        if ($course->lessons()->where(['section' => $lesson->section])->where('rank', '>', $lesson->rank)->count()) {
            $progress = $this->container->user->makeProgress($course, $lesson->section, $lesson->rank + 1);
        }/* elseif ($course->lessons()->where('section', '>', $lesson->section)->count()) {
            $progress = $this->container->user->makeProgress($course, $lesson->section + 1, 1);
        }*/

        return $this->success([
            'section' => $progress->section,
            'rank' => $progress->rank,
        ]);
    }

}