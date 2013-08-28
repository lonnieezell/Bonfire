## Reporting an Issue

Bonfire uses [GitHub Issue Tracking](https://github.com/ci-bonfire/Bonfire/issues) to track issues (primarily bugs and new code contributions). If you find a bug in Bonfire, this is the place to start. You will need a (free) [GitHub](http://github.com) account in order to help out.

<a name="creating-bug-reports"></a>
### Creating a Bug Report

If you have found a problem in Bonfire start by searching the existing issues at [GitHub](https://github.com/ci-bonfire/Bonfire/issues) to make sure it hasn't already been reported. If there is not existing issue you can [add a new one](https://github.com/ci-bonfire/Bonfire/issues/new).

At a minimum, your issue report needs a title and a description of the problem. But that's not all, that's just the minimum. You should include a code sample, if applicable, and the steps needed to recreate. It is also recommended to include your PHP version, and operating system. The goal is to make it easy for yourself--or others--to recreate and fix the bug.

You should also assign one of the labels to the issue. For most issues, simply assigning the 'Bug' label will be all you need to do. If you have found a truly critical bug that stops the execution of the program and makes it completely unusable (as opposed to just an annoyance or part not working) you can assign the 'Critical' label to it. Please be considerate of the developers, though, and don't use this label unless it is absolutely a show stopper.

Once you've submitted your report, don't hold your breath for a fix. Unless this is flagged as a "Critical, It Won't Work At All!" type of bug, you are creating the issue in the hopes that other developers will work with you to find a fix. There is no guarantee that someone else will jump in a fix the problem for you. Creating an issue like this is mostly to help yourself start on the path of fixing the problem and for others to confirm it with a "I'm having this problem too" comment.


<a name="feature-requests"></a>
### Submitting Feature Requests

You can submit a feature request in much the same was reporting a bug (above). This time, however, you need to make sure to add a 'Feature Request' label to the issue so that everyone can easily sort the issues.


<a name="fix-issues"></a>
## Helping Fix Existing Issues

The next step beyond reporting issues is to help the core team resolve existing issues. If you check the [Everyone's Issues](https://github.com/ci-bonfire/Bonfire/issues) list in GitHub, you're sure to find issues that already need resolving. Here's how you--regardless of your technical expertise--can help out.

<a name="verify-bugs"></a>
### Verifying Bug Reports

For starters, it helps to just verify bug reports. Can you reproduce the reported issue on your own computer? If so, you can add a comment to the issue saying that you're seeing the same thing.

If something is very vague, can you help squish it down into something specific? Maybe you can provide additional information to help reproduce a bug, or eliminate needless steps that aren't required to help demonstrate the problem.

Anything you can do to make bug reports more succinct or easier to reproduce is a help to folks trying to write code to fix those bugs--whether you end up writing the code yourself or not.

<a name="testing-patches"></a>
### Testing Patches

You can also help out by examining pull requests that have been submitted to Bonfire via GitHub. To apply someone's changes you need to first create a dedicated branch:

    $ git checkout -b testing_branch

Then you can use their remote branch to update your codebase. For example, let's say the GitHub user JohnSmith has forked and pushed to the topic branch located at https://github.com/JohnSmith/Bonfire.

    $ git remote add JohnSmith git://github.com/JohnSmith/Bonfire.git
    $ git pull JohnSmith topic

After applying their branch, test it out! Here are some things to think about:

* Does the change actually work?
* Are you happy with the tests? Can you follow what they're testing? Are there any tests missing?
* Does it have proper documentation coverage? Should documentation elsewhere be updated?
* Do you like the implementation? Can you think of a nicer or faster way to implement a part of their change?

Once you're happy that the pull request contains a good change, comment on the GitHub issue indicating your approval. Your comment should indicate that you like the change and what you like about it. Something like:

> I like the way you've restructured that code in generate_finder_sql, much nicer. The tests look good too.

If your comment simply says "+1", then odds are that other reviewers aren't going to take it too seriously. Show that you took the time to review the pull request.


<a name="bonfire-docs"></a>
## Contributing to Bonfire Documentation

Bonfire has two main sets of documentation: the Guides help you learn Bonfire, while the API is a reference.

You can help improve the guides by making them more coherent, adding missing information, correcting factual errors, fixing typos, bringing it up to date with the latest edge Bonfire. To get involved in the translation of Bonfire guides, please see [Translating Bonfire Guides]().

Changes are made directly within this wiki so please double-check all edits for correctness and good English. If you are a non-native English speaker, feel free to post on the [forums](http://forums.cibonfire.com) or post an issue here on GitHub asking someone to look over your English for the portion you edited.

If you are unsure of the documentation changes, you can create an issue in the Bonfire [issues tracker](https://github.com/ci-bonfire/Bonfire/issues?sort=created&direction=desc&state=open) on GitHub.

When working with documentation, please take into account the [API Documentation Guidelines](api_documentation_guidelines.html) and the [Coding Conventions](coding_conventions).

<a name="bonfire-code"></a>
## Contributing to the Bonfire Code

<a name="clone-repo"></a>
### Clone the Bonfire Repository

The first thing you need to do to be able to contribute code is to clone the repository:

    $ git clone git://github.com/ci-bonfire/Bonfire.git

and create a dedicated branch:

    $ cd Bonfire
    $ git checkout -b my_new_branch

It doesn't really matter what name you use, because this branch will only exist on your local computer.

<a name="write-code"></a>
### Write Your Code

Now get busy and add or edit code. You're on your branch now, so you can write whatever you want (you can check to make sure you're on the right branch with git branch -a). But if you're planning to submit your change back for inclusion in Bonfire, keep a few things in mind:

* Get the code right
* Use Bonfire idioms and classes
* Include tests that fail without your code, and pass with it
* Update the documentation, the surrounding one, examples elsewhere, guides, whatever is affected by your contribution

<a name="follow-conventions"></a>
### Follow the Coding Conventions

Bonfire follows a simple set of [Coding Conventions](coding_conventions) which are summarized here, but are given a more complete treatment in their own guide.

* Spaces, not tabs, are preferred.
* No trailing whitespace. Blank lines should not have any space.
* Prefer && / || over AND / OR.
* Always use brackets for foreach, if else, etc.
* Brackets on their own lines.
* Follow the conventions you see used in the source already.

<a name="sanity-check"></a>
### Sanity Check

You should not be the only person who looks at the code before you submit it. You know at least one other developer, right? Show them what you're doing and ask for feedback. Doing this in private before you push a patch out publicly is the "smoke test" for a patch: if you can't convince one other developer of the beauty of your code, you're unlikely to convince the core team either.

<a name="commit-changes"></a>
### Commit Your Changes

When you're happy with the code on your computer, you need to commit the changes to git:

    $ git commit -a -m "Here is a commit message on what I changed in this commit"

<a name="update-master"></a>
### Update Master

It's pretty likely that other changes to master have happened while you were working. Go get them:

    $ git checkout master
    $ git pull

Now reapply your patch on top of the latest changes:

    $ git checkout my_new_branch
    $ git rebase master

No conflicts? Change still seems reasonable to you? Then move on.

<a name="fork"></a>
### Fork

Navigate to the Bonfire [GitHub repository](https://github.com/ci-bonfire/Bonfire) and press "Fork" in the upper right hand corner.

Add the new remote to your local repository on your local machine:

    $ git remote add mine git@github.com:<your user name>/Bonfire.git

Push to your remote:

    $ git push mine my_new_branch

<a name="issue-pull-request"></a>
### Issue a Pull Request

Navigate to the Bonfire repository you just pushed to (e.g. https://github.com/your-user-name/Bonfire) and press "Pull Request" in the upper right hand corner.

Write your branch name in branch field (is filled with master by default) and press "Update Commit Range"

Ensure the changesets you introduced are included in the "Commits" tab and that the "Files Changed" incorporate all of your changes.

Fill in some details about your potential patch including a meaningful title. When finished, press "Send pull request." Bonfire Core will be notified about your submission.

<a name="iterate"></a>
### Iterate as Necessary

It's entirely possible that the feedback you get will suggest changes. Don't get discouraged: the whole point of contributing to an active open source project is to tap into community knowledge. If people are encouraging you to tweak your code, then it's worth making the tweaks and resubmitting.

And then...think about your next contribution!