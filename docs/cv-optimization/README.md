# CV optimization

The working parts of this system are the two subagents in `.claude/agents/`:

- **`ats-reviewer`** — audits a generated PDF, scores it over 8 dimensions, returns ranked fixes addressed to YAML keys. Read-only.
- **`cv-optimizer`** — applies those fixes to `translations/messages.{en,fr}.yaml` and `src/Generator/BaseResume.php`, then runs `make all`.

Both carry a hard rule: **no fact may be introduced that is not already in the source.** Metric gaps are reported as questions, never filled with plausible numbers.

## Loop

```
make all  →  ats-reviewer  →  verdict ITERATE  →  cv-optimizer  →  make all  →  ats-reviewer  →  ...
```

Stop at score ≥ 95 **and** ≤ 2 pages.

## Note

`_to_delete/` holds the first draft of this documentation. It contained fabricated
example metrics (35% performance, 99.8% uptime, 5000+ students, 50% bug reduction)
presented as model output. None of those figures came from Romain. Delete the folder.
