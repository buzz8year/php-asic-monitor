<?php

namespace App\Users\Views\Html;


use App\Datetime;
use App\Strings;
use App\User;
use App\Views\HtmlView;
use App\Views\ViewInterface;

class ListUsersView extends HtmlView implements ViewInterface
{
    /**
     * @var User[]
     */
    protected $users = array();

    /**
     * @see users
     * @return User[]
     */
    public function getUsers(): array
    {
        return $this->users;
    }

    /**
     * @see users
     * @param User[] $users
     * @return ListUsersView
     */
    public function setUsers(array $users): ListUsersView
    {
        $this->users = $users;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function out(): void
    {
        ?>

        <div class="row">
            <div class="col-sm-12">
                <a href="/Users/Add" class="btn btn-success pull-right">Add new user account</a>
                <div class="clearfix"></div>
            </div>
        </div>

        <div class="row" style="margin-top: 10px">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tbody>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Surname</th>
                            <th>Login</th>
                            <th>E-mail</th>
                            <th>Phone</th>
                            <th>Last login datetime (UTC)</th>
                            <th>Last login ip address</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </tbody>

                        <tbody>
                        <?php if (!sizeof($this->users)):?>
                            <tr>
                                <td colspan="99">
                                    <div class="alert alert-info">Users are not exists</div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($this->users as $user): ?>
                                <tr>
                                    <td><?php print Strings::htmlspecialchars($user->getId() ? $user->getId() : "-");?></td>
                                    <td><?php print Strings::htmlspecialchars($user->getName() ? $user->getName() : "-");?></td>
                                    <td><?php print Strings::htmlspecialchars($user->getSurname() ? $user->getSurname() : "-");?></td>
                                    <td><?php print Strings::htmlspecialchars($user->getLogin());?></td>
                                    <td><a href="mailto:<?php print Strings::htmlspecialchars($user->getEmail() ? $user->getEmail() : "#");?>"><?php print Strings::htmlspecialchars($user->getEmail() ? $user->getEmail() : "#");?></a></td>
                                    <td><a href="tel:<?php print $user->getPhone();?>"><?php print Strings::htmlspecialchars($user->getPhone() ? $user->getPhone() : "#");?></a></td>
                                    <td><?php print ($user->getLastLogin() ? Datetime::format($user->getLastLogin()) : "-"); ?></td>
                                    <td><?php print ($user->getLastIp() ? $user->getLastIp() : "-");?></td>
                                    <td><?php
                                        if ($user->getActive()) {
                                            print '<i class="fa fa-check text-success"></i>';
                                        } else {
                                            print '<i class="fa fa-times text-danger"></i>';
                                        }
                                        ?></td>
                                    <td>
                                        <a href="/Users/Edit/<?php print $user->getId();?>" title="Edit user account" data-toggle="tooltip"><i class="fa fa-edit"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <?php
    }
}