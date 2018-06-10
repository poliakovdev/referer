<?php

namespace App\Component\TaskModifier;


use App\Entity\ProjectFlow;
use App\Entity\TaskInterface;

class RefererModifier implements TaskModifier
{
    /**
     * @var $type;
     */
    private $type;
    /**
     * @var $items;
     */
    private $items;
    /**
     * @param TaskInterface $task
     * @param ProjectFlow $flow
     * @return TaskInterface
     */
    private function modifyRefererByType(){
        switch ($this->type){
            case "direct":
                $referer = null;
                break;
            case "social":
                $referer = $this->randomItem();
                break;
            case "referral":
                $referer = $this->randomItem();
                break;
            case "organic":
                $referer = $this->randomItem();
                $referer = ["q" => $referer];
                $referer = "http://google.com/?".http_build_query($referer);
                break;
        }
        return $referer;
    }

    private function randomItem(){
        $referer = $this->items[array_rand($this->items)];
        return $referer;
    }

    public function modify(TaskInterface $task, ProjectFlow $flow): TaskInterface
    {
        $trafficType = $flow->getTrafficType();

        $this->type = $trafficType->getType();
        $this->items = $trafficType->getItems();

        $referer = null;
        $referer = $this->modifyRefererByType();

        return $task->setReferer($referer);
    }



}