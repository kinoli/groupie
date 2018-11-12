# Groupie plugin for Craft CMS 3.x

Assign users to specific user groups upon registration. Especially useful for front-end signup forms.

## Requirements

This plugin requires Craft CMS 3.0.0-beta.23 or later.

## Groupie Overview

The plugin allows you to choose which user groups you want to allow members to register into, via front end registration forms. You can configure it to allow for registrations into multiple groups or restrict it to one group.

![Screenshot](resources/img/user-account.png)

## Configuring Groupie

Groupie is quite easy to setup. Configure the appropriate settings in the CraftCMS control panel.

![Screenshot](resources/img/settings.png)

## Using Groupie

In your front-end `users/save-user` form, just make sure one of your POST variables is `groups`. It can either be a string or an array.

A hidden field would work fine, or a select option or even checkboxes via `name="groups[]"`.

Brought to you by [Jesse Knowles](http://www.jesseknowles.com)
