# GitHub Sharing Guide

This guide explains how to share this system using a GitHub repository.

## 1. Prepare the project before uploading

Before pushing the project to GitHub, make sure you do **not** upload sensitive files such as:

- `.env`
- API keys
- database backups with real user data
- private certificates
- local IDE or machine-specific config files

If your `.env` contains secrets, keep it local and create an `.env.example` file instead.

Example `.env.example`:

```env
APP_NAME=BoutiquePOS
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

## 2. Check `.gitignore`

Make sure files that should stay local are ignored. Common examples:

```gitignore
/vendor
/node_modules
.env
.idea
.vscode
*.log
```

Laravel projects usually already have a `.gitignore`, but it is still worth checking.

## 3. Initialize Git if needed

If the project is not yet a Git repository:

```bash
git init
git add .
git commit -m "Initial commit"
```

If Git is already initialized, just review your changes:

```bash
git status
```

## 4. Create a new GitHub repository

On GitHub:

1. Sign in to your GitHub account.
2. Click `New repository`.
3. Enter a repository name, for example `boutique-pos`.
4. Choose `Public` or `Private`.
5. Click `Create repository`.

Recommended:

- Use `Private` if the system contains business logic or internal files you do not want public.
- Use `Public` only if you are sure there are no secrets or sensitive assets included.

## 5. Connect the local project to GitHub

Copy the repository URL from GitHub, then run:

```bash
git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPOSITORY.git
git branch -M main
git push -u origin main
```

If `origin` already exists, update it instead:

```bash
git remote set-url origin https://github.com/YOUR_USERNAME/YOUR_REPOSITORY.git
git push -u origin main
```

## 6. Add a helpful README

Your repository is easier to share if the README includes:

- project name
- system overview
- setup steps
- required tools
- environment setup
- database migration or seed steps
- login/test account info if safe to share

This project already has a `README.md`, so you can improve that file over time.

## 7. Share the repository

After pushing, you can share:

- the GitHub repository link
- direct links to documentation files
- collaborator access through GitHub repository settings

If the repository is private:

1. Open the repository on GitHub.
2. Go to `Settings`.
3. Open `Collaborators and teams`.
4. Invite the people who need access.

## 8. Recommended final check

Before sharing, confirm:

- no secrets were committed
- `.env` is not uploaded
- large unnecessary files are excluded
- README and setup docs are clear
- the project can be cloned and set up by another developer

## Quick Command Summary

```bash
git init
git add .
git commit -m "Initial commit"
git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPOSITORY.git
git branch -M main
git push -u origin main
```

## Optional next step

If you want, I can also create:

- an `.env.example`
- a cleaner `README.md`
- a deployment guide
- a collaborator onboarding checklist
