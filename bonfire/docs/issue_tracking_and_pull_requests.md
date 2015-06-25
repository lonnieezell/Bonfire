# Submitting Bug Reports and Feature Requests

Please read this document before submitting an issue or pull request the CI-Bonfire project.
It really helps us out and lets us know that you respect our time.
In return, we will show the same respect in addressing your issue.

If you think this guide can be improved, please let us know.

## Types of issues<a name="types-of-issues"></a>

The GitHub issue tracker should only be used for one of the following:

+ **Bugs** - when a feature of the project has been _identified as
  broken_.
+ **Feature requests** - when you ask for a _new feature_ to be added to a
  Bonfire.
+ **Contribution enquiries** - when you want to discuss whether a _new
  feature_ or _change_ would be accepted in Bonfire before you begin
development work on it.

These are some things that don't belong in the issue tracker:

+ **Please avoid personal support requests.** We try to provide the best support
we can, while maintaining a free project that helps you code faster.  We have forums,
user guides, API doc's, CodeIgniter has great user doc's and a execellent forum.

+ **Please avoid derailing issues.** Keep the discussion on topic and respect the
opinions of others.

## Bugs<a name="bugs"></a>

A bug is a _demonstrable problem_ that is caused by the code in the
repository.

If you've come across a problem with the code and you're letting us know about
it, _thank you_. We appreciate your time and the effort you're making to help
improve the code for everyone else!

Please read the following guidelines for reporting bugs:

1. **Use the GitHub issue search** - check if the issue has already been
   reported. If it has been, please comment on the existing issue.

2. **Check if the issue has been fixed** - the latest `master` or
   development branch may already contain a fix.

3. **Isolate the demonstrable problem** - make sure that the code in the
   project's repository is _definitely_ responsible for the issue. Create a
[reduced test case](http://css-tricks.com/6263-reduced-test-cases/) - an
extremely simple and immediately viewable example of the issue.

4. **Include a live example** - provide a link to your reduced test case
   when appropriate (e.g. if the issue is related to frond-end technologies).
Please use [jsFiddle](http://jsfiddle.net) to host examples.

Please try to be as detailed as possible in your report too. What is your
environment? What steps will reproduce the issue? What browser(s) and OS
experience the problem? What would you expect to be the outcome? All these
details will help me and others to assess and fix any potential bugs.

### Example of a good bug report:

> Short and descriptive title
>
> A summary of the issue and the browser/OS environment in which it occurs. If
> suitable, include the steps required to reproduce the bug.
>
> 1. This is the first step
> 2. This is the second step
> 3. Further steps, etc.
>
> `<url>` - a link to the reduced test case
>
> Any other information you want to share that is relevant to the issue being
> reported. This might include the lines of code that you have identified as
> causing the bug, and potential solutions (and your opinions on their
> merits).

A good bug report shouldn't leave us needing to chase you up to get further
information that is required to assess or fix the bug.

## Feature requests<a name="feature-requests"></a>

Feature requests are welcome! Please provide links to examples or articles that
help to illustrate the specifics of a feature you're requesting. The more
detail, the better. It will help us to decide whether the feature is something we
agree should become part of the project.

## Contribution enquiries<a name="enquiries"></a>

Contribution enquiries should take place before any significant pull request,
otherwise you risk spending a lot of time working on something that we might not
want to pull into the repository.

In this regard, some contribution enquires may be feature requests that you
would like to have a go at implementing yourself if they are wanted. Other
enquiries might revolve around refactoring code or porting a project to
different languages.


## Pull requests<a name="pull-requests"></a>

Good pull requests - patches, improvements, new features - are a fantastic
help.

If you've spotted any small, obvious errors and want to help out by patching it,
that will be much appreciated.

If your contribution involves a significant amount of work or substantial
changes to any part of the project, please open a "contribution enquiry" issue
first to check that the work is wanted or matches the goals of the project.

All pull requests should remain focused in scope and avoid containing unrelated
commits.

Please follow this process; it's the best way to get your work merged into the
project:

1. [Fork](http://help.github.com/fork-a-repo/) the project.
2. Clone your fork ( `git clone
   git@github.com:<your-username>/<repo-name>git`).
3. Add an `upstream` remote (`git remote add upstream
   git://github.com/<upsteam-owner>/<repo-name>.git`).
4. Get the latest changes from upstream (e.g. `git pull upstream
   <dev-branch>`).
5. Create a new topic branch to contain your feature, change, or fix ( `git
   checkout -b <topic-branch-name>` ).
6. Make sure that your changes adhere to the current [coding conventions](coding_conventions) used
   throughout the project - indentation, accurate comments, etc.
7. Commit your changes in logical chunks. Please adhere to these [git commit
   message guidelines](http://tbaggery.com/2008/04/19/a-note-about-git-commit-messages.html)
   or your pull request is unlikely be merged into the main project.
8. Push the branch up to your fork ( `git push origin <topic-branch-name>` ).
9. [Open a Pull Request](http://help.github.com/send-pull-requests/) with a
   clear title and description. Please mention which browsers you tested in.

