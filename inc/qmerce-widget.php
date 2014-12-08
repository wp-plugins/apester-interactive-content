<?php

class QmerceWidget extends WP_Widget {

    /**
     * @var QmerceTagComposer
     */
    private $tagComposer;

    /**
     * Constructor function
     */
    public function QmerceWidget()
    {
        $this->tagComposer = new QmerceTagComposer();
        $this->WP_Widget('qmerce_widget', 'Qmerce Challenge Widget', array('description' => 'Chosen automated Qmerce challenge from your `My Stuff` inventory'));
    }

    /**
     * Hook callback for widget view
     * @param array $args
     * @param array $instance
     */
    public function widget( $args, $instance )
    {
        echo $this->tagComposer->composeAutomationTag();
    }
}