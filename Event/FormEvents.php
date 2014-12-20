<?php

namespace Asapo\Bundle\Sulu\FormBundle\Event;

class FormEvents
{
    const CONTACT_FORM_SUCCESS = 'asapo_sulu_form.form.success';
    const CONTACT_FORM_SUCCESS_SINGLE = 'asapo_sulu_form.form.<name>.success';

    public static function getSuccessEventName($name)
    {
        return str_replace('<name>', $name, self::CONTACT_FORM_SUCCESS_SINGLE);
    }

    private function __construct()
    {
    }
}
