code.ign.com
=======================

Introduction
------------
This project is a modular Zend Framework 2 (ZF2) application that runs the Engineering site for IGN Entertainment.
We hope that by making it a public Git repo, others can look at is for examples of how to use ZF2.


Installation
------------
The easiest way to get a working copy of this project is to do a recursive
clone:

    git clone --recursive git@github.com:ign/code.ign.com.git

After the clone is complete, set up a virtual host to point to the public/
directory of the project and you should be ready to go!

If you're wondering what the `--recursive` flag is, keep reading:

Git Submodules
--------------

* [IgnArticle](https://github.com/ign/IgnArticle)
* [IgnGravatar](https://github.com/ign/IgnGravatar)
* [IgnLess](https://github.com/ign/IgnLess)
* [ZF2](https://github.com/zendframework/zf2)


This project makes use of [Git submodules](http://book.git-scm.com/5_submodules.html).
Utilizing Git submodules allows us to reference an exact commit in the upstream
[zendframework/zf2](https://github.com/zendframework/zf2) repository and ensure
that those who have cloned the project have that same commit checked out. This
provides several benefits:

* Developers do not have to worry about which commit of the zf2 project to have
  checked out for this project to work.
* No additional steps to "install" Zend Framework are needed; it "just works"
  after a cloning the project.

There are a couple of mild caveats to be aware of:

* Be sure to always run `git submodule update` after pulling, as merge/rebase
  does not automatically update the checked out commit in submodules if it has
  been changed.
* The initial clone will be a bit slower, due to it having to pull down a
  separate copy of ZF2 from what you already have.
