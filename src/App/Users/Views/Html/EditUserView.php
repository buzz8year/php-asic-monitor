<?php

namespace App\Users\Views\Html;


class EditUserView extends AddUserView
{
    /**
     * @var string
     */
    protected $form_name = "Edit user account form";
    /**
     * @var bool
     */
    protected $disable_login = true;

    /**
     * @inheritdoc
     */
    public function getAction(): string
    {
        return sprintf("/Users/Edit/%u/Save", $this->getUser()->getId());
    }

    /**
     * @inheritdoc
     */
    protected function getPasswordTitle(): string
    {
        return "New password";
    }

    /**
     * @inheritDoc
     */
    protected function getPasswordValue(): ?string
    {
        return null;
    }


}