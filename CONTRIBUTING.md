# Sistema de Gestão Hospitalar e Clinicas Sandra

## Contribuições

Nó esperamos que todos que contribuam com o projeto sigam a padronização MVC do Codeigniter 4
[style guide](https://github.com/emanuelpv/sandra/blob/develop/contributing/styleguide.rst),
comentados (dentro dos arquivos PHP do projeto), seja também documentada conforme ([user guide](https://codeigniter4.github.io/userguide/)), bem como, sejam realizados teste unitários conforme ([test folder](https://github.com/emanuelpv/sandra/tree/develop/tests)).


Observe que esperamos que todas as alterações de código ou correções de bugs sejam acompanhadas por um ou mais testes adicionados ao nosso conjunto de testes
para provar que o código funciona. Se as solicitações pull não forem acompanhadas de testes relevantes, elas provavelmente serão encerradas.
Como somos uma equipe de voluntários, não temos mais tempo para trabalhar na estrutura do que você. Por favor
torne a inclusão de suas contribuições o mais simples possível. Se precisar de ajuda para fazer testes
rodando em suas máquinas locais, peça ajuda nos fóruns. Ficaremos felizes em ajudar.
Siga as orientações do [Open Source Guide](https://opensource.guide/) que é uma boa primeira leitura para aqueles que são novos na contribuição para o código aberto!

## Issues

Issues são uma maneira rápida de apontar um bug. Se você encontrar um bug ou erro de documentação no sistema SANDRA, certifique-se de que:

1. Já não tenha uma questão aberta [Issue](https://github.com/emanuelpv/sandra/issues)
2. O problema ainda não foi corrigido (verifique o branch de desenvolvimento ou procure por [closed Issues](https://github.com/emanuelpv/sandra/issues?q=is%3Aissue+is%3Aclosed))
3. Não é algo realmente óbvio que você possa consertar sozinho

Reporting Issues is helpful, but an even [better approach](./contributing/workflow.rst) is to send a
[Pull Request](https://help.github.com/en/articles/creating-a-pull-request), which is done by
[Forking](https://help.github.com/en/articles/fork-a-repo) the main repository and making
a [Commit](https://help.github.com/en/desktop/contributing-to-projects/committing-and-reviewing-changes-to-your-project)
to your own copy of the project. This will require you to use the version control system called [Git](https://git-scm.com/).

## Guidelines

Before we look into how to contribute to CodeIgniter4, here are some guidelines. If your Pull Requests fail
to pass these guidelines, they will be declined, and you will need to re-submit when you’ve made the changes.
This might sound a bit tough, but it is required for us to maintain the quality of the codebase.

### PHP Style

All code must meet the [Style Guide](./contributing/styleguide.rst).
This makes certain that all submitted code is of the same format as the existing code and ensures that the codebase will be as readable as possible.

### Documentation

If you change anything that requires a change to documentation, then you will need to add to the documentation. New classes, methods, parameters, changing default values, etc. are all changes that require a change to documentation. Also, the [changelog](https://codeigniter4.github.io/CodeIgniter4/changelogs/index.html) must be updated for every change, and [PHPDoc](https://github.com/emanuelpv/sandra/blob/develop/phpdoc.dist.xml) blocks must be maintained.

### Compatibility

CodeIgniter4 requires [PHP 7.3](https://php.net/releases/7_3_0.php).

### Branching

CodeIgniter4 uses the [Git-Flow](http://nvie.com/posts/a-successful-git-branching-model/) branching model
which requires all Pull Requests to be sent to the __"develop"__ branch; this is where the next planned version will be developed.

The __"master"__ branch will always contain the latest stable version and is kept clean so a "hotfix" (e.g. an
emergency security patch) can be applied to the "master" branch to create a new version, without worrying
about other features holding it up. For this reason, all commits need to be made to the "develop" branch,
and any sent to the "master" branch will be closed automatically. If you have multiple changes to submit,
please place all changes into their own branch on your fork.

**One thing at a time:** A pull request should only contain one change. That does not mean only one commit,
but one change - however many commits it took. The reason for this is that if you change X and Y,
but send a pull request for both at the same time, we might really want X but disagree with Y,
meaning we cannot merge the request. Using the Git-Flow branching model you can create new
branches for both of these features and send two requests.

A reminder: **please use separate branches for each of your PRs** - it will make it easier for you to keep
changes separate from each other and from whatever else you are doing with your repository!

### Signing

You must [GPG-sign](./contributing/signing.rst) your work, certifying that you either wrote the work or
otherwise have the right to pass it on to an open-source project. This is *not* just a "signed-off-by"
commit, but instead, a digitally signed one.

### Static Analysis on PHP code

We cannot, at all times, guarantee that all PHP code submitted on pull requests to be working well without
actually running the code. For this reason, we make use of two static analysis tools, [PHPStan][1]
and [Rector][2] to do the analysis for us.

These tools have already been integrated into our CI/CD workflow to minimize unannounced bugs. Pull requests
are expected that their code will pass these two. In your local machine, you can manually run these tools
so that you can fix whatever errors that pop up with your submission.

PHPStan is expected to scan the entire framework by running this command in your terminal:

	vendor/bin/phpstan analyse

Rector, on the other hand, can be run on the specific files you modified or added:

	vendor/bin/rector process --dry-run path/to/file

[1]: https://github.com/phpstan/phpstan-src
[2]: https://github.com/rector/rector

### Breaking Changes

In general, any change that would disrupt existing uses of the framework is considered a "breaking change" and will not be favorably considered. A few specific examples to pay attention to:

1. New classes/properties/constants in `system` are acceptable, but anything in the `app` directory that will be used in `system` should be backwards-compatible.
2. Any changes to non-private methods must be backwards-compatible with the original definition.
3. Deleting non-private properties or methods without prior deprecation notices is frowned upon and will likely be closed.
4. Deleting or renaming public classes and interfaces, as well as those not marked as `@internal`, without prior deprecation notices or not providing fallback solutions will also not be favorably considered.

## How-to Guide

The best way to contribute is to fork the CodeIgniter4 repository, and "clone" that to your development area. That sounds like some jargon, but "forking" on GitHub means "making a copy of that repo to your account" and "cloning" means "copying that code to your environment so you can work on it".

1. Set up Git ([Windows](https://git-scm.com/download/win), [Mac](https://git-scm.com/download/mac), & [Linux](https://git-scm.com/download/linux)).
2. Go to the [CodeIgniter4 repository](https://github.com/emanuelpv/sandra).
3. [Fork](https://help.github.com/en/articles/fork-a-repo) it (to your Github account).
4. [Clone](https://help.github.com/en/articles/cloning-a-repository) your CodeIgniter repository: `git@github.com:\<your-name>/CodeIgniter4.git`
5. Create a new [branch](https://help.github.com/en/articles/about-branches) in your project for each set of changes you want to make.
6. Fix existing bugs on the [Issue tracker](https://github.com/emanuelpv/sandra/issues) after confirming that no one else is working on them.
7. [Commit](https://help.github.com/en/desktop/contributing-to-projects/committing-and-reviewing-changes-to-your-project) the changed files in your contribution branch.
8. Commit messages are expected to be descriptive of what you changed specifically. Commit messages like
"Fixes #1234" would be asked by the reviewer to be revised.
9. If there are intermediate commits that are not meaningful to the overall PR, such as "Fixed error on style guide", "Fixed phpstan error", "Fixing mistake in code", and other related commits, it is advised to squash your commits so that we can have a clean commit history.
10. If you have touched PHP code, run static analysis.
11. Run unit tests on the specific file you modified. If there are no existing tests yet, please create one.
12. Make sure the tests pass to have a higher chance of merging.
13. [Push](https://docs.github.com/en/github/using-git/pushing-commits-to-a-remote-repository) your contribution branch to your fork.
14. Send a [pull request](https://docs.github.com/en/github/collaborating-with-issues-and-pull-requests/creating-a-pull-request-from-a-fork).

The codebase maintainers will now be alerted to the submission and someone from the team will respond. If your change fails to meet the guidelines, it will be rejected or feedback will be provided to help you improve it.

Once the maintainer handling your pull request is satisfied with it they will approve the pull request and merge it into the "develop" branch. Your patch will now be part of the next release!

### Keeping your fork up-to-date

Unlike systems like Subversion, Git can have multiple remotes. A remote is the name for the URL of a Git repository. By default, your fork will have a remote named "origin", which points to your fork, but you can add another remote named "codeigniter", which points to `git://github.com/emanuelpv/sandra.git`. This is a read-only remote, but you can pull from this develop branch to update your own.

If you are using the command-line, you can do the following to update your fork to the latest changes:

1. `git remote add codeigniter git://github.com/emanuelpv/sandra.git`
2. `git pull codeigniter develop`
3. `git push origin develop`

Your fork is now up to date. This should be done regularly and, at the least, before you submit a pull request.

## Translations Installation

If you wish to contribute to the system message translations,
then fork and clone the [translations repository](https://github.com/codeigniter4/translations)
separately from the codebase.

These are two independent repositories!
