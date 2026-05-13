# GitHub Workflow

## Commit-after-every-phase rule
After completing each phase, run:
```bash
git add -A
git commit -m "Phase X: <conventional commit message>"
git push origin main
```

## Branch strategy
- `main` is the only branch
- Every phase is a commit on main
- GitHub Actions runs Pint + Pest on every push

## GitHub is the only backup
If a push fails, halt and resolve before continuing the next phase.
