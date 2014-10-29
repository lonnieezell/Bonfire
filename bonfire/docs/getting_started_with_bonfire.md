# Getting Started with Bonfire

## Guide Assumptions

This guide is designed for developers that are familiar with CodeIgniter, but are just getting started with Bonfire.  It does not assume that you have any prior experience with Bonfire.

Bonfire is intended to provide a kickstart for new web applications built in CodeIgniter. If you don't know CodeIgniter you will need to get to speed with that first. There are a number of free tutorials out there, including:

* [CodeIgniter From Scratch Series - at NetTuts](http://net.tutsplus.com/sessions/codeigniter-from-scratch/)
* [Tutorials at the CodeIgniter Wiki](http://codeigniter.com/wiki/Category:Help::Tutorials)

## What is Bonfire?

Bonfire is a framework for your web application, built on top of the CodeIgniter PHP Framework. It is not a CMS, but a starting point for new projects that require ready-made tools like:

* Robust Role-Based-Access-Control
* Fully Modular Codebase. Built around HMVC.
* Database backup, migration, and maintenance.
* Powerful, parent/child capable theme engine.
* Simple Email queue to keep your ISP happy.
* and more...

### Bonfire Components

Bonfire ships with a number of individual components that are described below. Don't get hung up on the details of of each component for now. Many of these will be described in more detail below.

#### MY_Model

MY_Model provides a robust set of standard methods for you to derive your models from. Methods covering all standard CRUD routines, as well as simple methods that chain together. For simple models, all you have to do is extend the MY_Model class and set a couple of variables and you'll be up and running without any extra code.

#### MY_Controller

MY_Controller provides 4 different controllers that you can use to keep common functions within each 'zone' of your website: Base_Controller, Front_Controller, Authenticated_Controller and Admin_Controller. You can set different defaults in each controller for a different part of your site. For example, setting the admin theme in the Admin_Controller, or making sure the user is logged in with the Authenticated_Controller.


#### Role-Based Access Control

Bonfire's User module provides a flexible User_model, ready for your users to login with, as well as a flexible RBAC that is simple to use and flexible enough to fit most needs.

#### Database Tools

Quickly browse your database, perform backups, restore old bakups, and keep your database versioned with Migrations. Unlike CodeIgniter's built-in migrations, Bonfire extends them to allow for the core, your app, and each module to maintain their own set of migrations.

#### System Events

Very similar to CodeIgniter's Hooks, System Events allow you to hook into Bonfire's core code without modifying core files. It also provides a simple way for you to add hooks to your own code for other modules to use.

#### Activities Log

This library provides a simple way to log user activities, such as 'JohnDoe deleted the Page titled "Page 1"'. This makes it simple to keep a clear, consistent log of every important action of every user.

#### Settings

Easily store application-wide settings in the database, allowing your users to change settings simply and easily.


## Creating A New Bonfire Project

The best way to use this guide is to follow along, step-by-step, entering the code at each step. Everything you need to make this project work is included in this tutorial.

During this tutorial we will create a (simple) blog module that will have you mastering your way around Bonfire in no time.


## Navigating Bonfire

### Contexts

Once you log into the admin portion of Bonfire, you will find a menu with 4 categories across the top: Content, Reports, Settings, and Developer. These four categories are what Bonfire calls **Contexts**.

A Context is a way to group related content from different modules. It all happens automatically based upon the names of the controller and a little behind-the-scenes magic in the routes file. You don't need to understand all of the details about contexts just yet, but we will touch on how to use them in this tutorial.

You can create as many custom contexts as you want, and even remove the Content and Reports contexts, to meet the needs of your app. The Settings and Developer contexts are a central part of how Bonfire works and cannot be removed.

Contexts don't have to be visible to everyone who uses your admin area, though. They can be hidden individually per user role.

### Modules

Bonfire is primarily a collection of modules that handle all of the various parts. This makes it easy to create your own modules that can be reused and passed around with a minimum of work.

If you navigate the project, look in the main *bonfire* folder. You will find the following folder structure:

Folder      | Purpose
------------|---------------
application | Holds Bonfire's primary files and the core modules.
codeigniter | Holds the CodeIgniter system files.
modules     | Holds your own custom modules.
themes      | Holds all of your themes.

A module is a mini-application that can contain assets (like CSS or JS), config files, controllers, models, libraries, helpers, and views. This is all powered by [Modular Extensions HMVC](https://bitbucket.org/wiredesignz/codeigniter-modular-extensions-hmvc/wiki/Home) and allows for HMVC usage (which we will touch on later in the tutorial).

---

Next: [A Simple Blog Tutorial](tut_blog)