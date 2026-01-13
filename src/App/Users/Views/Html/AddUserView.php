<?php

namespace App\Users\Views\Html;

use App\Bootstrap3\Helpers\ResultError;
use App\Bootstrap3\Helpers\Elements;
use App\Location;
use App\Strings;
use App\User;
use App\Views\HtmlView;
use App\Views\ViewInterface;

class AddUserView extends HtmlView implements ViewInterface
{
    /**
     * @var string
     */
    protected $form_name = "Create new user account form";
    /**
     * @var bool
     */
    protected $disable_login = false;
    /**
     * @var User
     */
    protected $user;
    /**
     * @var array
     */
    protected $user_rights = array();
    /**
     * @var Location[]
     */
    protected $locations = array();

    /**
     * @see user
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @see user
     * @param User $user
     * @return AddUserView
     */
    public function setUser(User $user): AddUserView
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @see user_rights
     * @return array
     */
    public function getUserRights(): array
    {
        return $this->user_rights;
    }

    /**
     * @see user_rights
     * @param array $user_rights
     * @return AddUserView
     */
    public function setUserRights(array $user_rights): AddUserView
    {
        $this->user_rights = $user_rights;
        return $this;
    }

    /**
     * @see locations
     * @return Location[]
     */
    public function getLocations(): array
    {
        return $this->locations;
    }

    /**
     * @see locations
     * @param Location[] $locations
     * @return AddUserView
     */
    public function setLocations(array $locations): AddUserView
    {
        $this->locations = $locations;
        return $this;
    }

    /**
     * @return string
     */
    protected function getAction(): string
    {
        return "/Users/Add/Save";
    }

    /**
     * @return string
     */
    protected function getPasswordTitle(): string
    {
        return "Password";
    }

    /**
     * @return null|string
     */
    protected function getPasswordValue(): ?string
    {
        return $this->user->getPassword();
    }

    /**
     * @inheritDoc
     */
    public function out(): void
    {
        ?>

        <?php (new ResultError($this->getResult()))->out(); ?>




        <form class="form-horizontal" action="<?php print $this->getAction();?>" method="post">
            <div class="row">
                <div class="col-sm-9 col-sm-offset-3">
                    <h3><?php print $this->form_name;?></h3>
                </div>
            </div>
            <?php

            (new Elements\Text())
                ->setTitle("Name")
                ->setName("name")
                ->setValue(Strings::htmlspecialchars($this->user->getName()))
                ->setPlaceholder("User name")
                ->setMaxLength(255)
                ->setRequire(false)
                ->out()
            ;

            (new Elements\Text())
                ->setTitle("Surname")
                ->setName("surname")
                ->setValue(Strings::htmlspecialchars($this->user->getSurname()))
                ->setPlaceholder("User surname")
                ->setMaxLength(255)
                ->setRequire(false)
                ->out()
            ;

            (new Elements\Text())
                ->setTitle("Phone")
                ->setName("phone")
                ->setValue(Strings::htmlspecialchars($this->user->getPhone()))
                ->setPlaceholder("Mobile phone")
                ->setMaxLength(20)
                ->setRequire(false)
                ->out()
            ;

            (new Elements\Text())
                ->setTitle("E-mail")
                ->setName("email")
                ->setValue(Strings::htmlspecialchars($this->user->getEmail()))
                ->setPlaceholder("User e-mail")
                ->setMaxLength(255)
                ->setRequire(false)
                ->out()
            ;

            (new Elements\Text())
                ->setTitle("Login")
                ->setName("login")
                ->setValue(Strings::htmlspecialchars($this->user->getLogin()))
                ->setPlaceholder("User login")
                ->setMaxLength(255)
                ->setRequire(true)
                ->setDisabled(isset($this->disable_login) && $this->disable_login)
                ->out()
            ;

            (new Elements\Password())
                ->setTitle($this->getPasswordTitle())
                ->setName("password")
                ->setValue(Strings::htmlspecialchars($this->getPasswordValue()))
                ->setPlaceholder("User password")
                ->setMaxLength(255)
                ->setRequire(true)
                ->out()
            ;

            (new Elements\Checkboxes())
                ->setTitle("Options")
                ->addCheckbox(
                    (new Elements\Checkbox())
                        ->setTitle("Active")
                        ->setName("active")
                        ->setValue("1")
                        ->setChecked($this->user->getActive() && true)
                )
                ->out();

            ?>

            <div class="row">
                <div class="col-sm-9 col-sm-offset-3">
                    <h3>User rights</h3>
                </div>
            </div>

            <?php

            $checkboxes = (new Elements\Checkboxes())
                ->setTitle("Access user rights");

            foreach ($this->getUserRights() as $right_id => $right_value) {
                $checkboxes->addCheckbox(
                    (new Elements\Checkbox())
                        ->setTitle($right_value)
                        ->setName("user_rights[]")
                        ->setValue($right_id)
                        ->setChecked($this->getUser()->hasAccess($right_id))

                );
            }

            $checkboxes->out();

            ?>


            <div class="row">
                <div class="col-sm-9 col-sm-offset-3">
                    <h3>Available locations</h3>
                </div>
            </div>

            <?php

            $checkboxes = (new Elements\Checkboxes())
                ->setTitle("User available locations");

            foreach ($this->getLocations() as $location) {
                $checkboxes->addCheckbox(
                    (new Elements\Checkbox())
                        ->setTitle($location->getName())
                        ->setName("user_locations[]")
                        ->setValue($location->getId())
                        ->setChecked($this->getUser()->isLocationAllowed($location->getId()))

                );
            }

            $checkboxes->out();

            ?>

            <div class="col-sm-9 col-sm-offset-3">
                <button type="submit" class="btn btn-success">Save</button>
            </div>

        </form>

        <?php
    }

}