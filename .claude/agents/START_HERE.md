# 🚀 START HERE

Your CV optimization agent system is ready to use.

## What You Have

6 files in `.claude/agents/`:

1. **README.md** — Overview of the system
2. **WORKFLOW.md** — Step-by-step guide (READ THIS FIRST)
3. **AGENT_REVIEWER.md** — Copy-paste prompt for Agent 1 (ATS auditor)
4. **AGENT_OPTIMIZER.md** — Copy-paste prompt for Agent 2 (CV improver)
5. **YAML_MAPPING.md** — Reference for which YAML keys to edit
6. **CHECKLIST.md** — Track progress on all 4 CVs

## The System

```
Agent 1 audits your PDF → Gives ATS score + YAML recommendations
        ↓
Agent 2 generates improved YAML values → Ready to copy-paste
        ↓
You update translations files → Run make all
        ↓
Agent 1 re-audits new PDFs → Loop until score ≥95
```

## Next Steps

1. **Read:** `.claude/agents/WORKFLOW.md` (15 minutes)
2. **Install:** `pip install pdfplumber --break-system-packages`
3. **Copy:** Agent 1 prompt from `AGENT_REVIEWER.md` → paste into Claude
4. **Provide:** Your CV's PDF text + resume.json + current YAML translations
5. **Get:** ATS score & YAML recommendations
6. **Copy:** Agent 2 prompt from `AGENT_OPTIMIZER.md` → paste into Claude
7. **Get:** Ready-to-paste improved YAML values
8. **Update:** `translations/messages.en.yaml` (or messages.fr.yaml)
9. **Run:** `make all`
10. **Re-audit:** Send new PDF back to Agent 1
11. **Loop:** Until score ≥95

## Timeline

- **Per CV:** 30-45 minutes
- **All 4 CVs:** 2-3 hours
- **Total effort:** Copy-paste prompts, update YAML, run `make all`

## Key Advantage

Unlike traditional PDF editing:
- ✅ Works with your PHP code (source of truth)
- ✅ Changes are reversible
- ✅ Everything regenerates automatically
- ✅ Completely traceable

## Files Location

```
~/Projects/haux49/programmatic-resume/
.claude/agents/
├── START_HERE.md          ← You are here
├── README.md
├── WORKFLOW.md            ← Read this next
├── AGENT_REVIEWER.md      ← Copy-paste prompt 1
├── AGENT_OPTIMIZER.md     ← Copy-paste prompt 2
├── YAML_MAPPING.md        ← Reference
├── CHECKLIST.md           ← Track progress
```

## Commands You'll Use

```bash
# Install
pip install pdfplumber --break-system-packages

# Extract PDF text for agents
python3 -c "import pdfplumber; [print(page.extract_text()) for page in pdfplumber.open('output/romain-sickenberg-backend-en.pdf').pages]"

# Regenerate PDFs after YAML updates
make all

# Validate YAML syntax
python3 -c "import yaml; yaml.safe_load(open('translations/messages.en.yaml'))"
```

## Success Criteria

✅ All 4 CVs score ≥95/100
✅ All improvements authentic (real metrics only)
✅ All translations files updated and committed
✅ Ready to use with recruiters

## You're Ready!

Read WORKFLOW.md next, then follow the step-by-step guide.

Any questions? Check WORKFLOW.md—most answers are there.

Good luck! 🎉

