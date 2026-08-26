# Implementation Checklist

Track your progress optimizing all 4 CVs.

## Pre-Implementation

- [ ] Read `.claude/agents/README.md`
- [ ] Read `.claude/agents/WORKFLOW.md`
- [ ] Install pdfplumber: `pip install pdfplumber --break-system-packages`
- [ ] Create output directories: `mkdir -p output/optimized output/audits`

## CV 1: romain-sickenberg-backend-en

### Iteration 1
- [ ] Extract resume.json, PDF text, current messages.en.yaml
- [ ] Run Agent 1 audit → Get score & YAML recommendations
- [ ] Save audit to: `output/audits/backend-en-audit-v1.json`
- [ ] Run Agent 2 optimization → Get improved YAML values
- [ ] Update translations/messages.en.yaml with improvements
- [ ] Run `make all` to regenerate PDFs
- [ ] Extract new PDF text
- [ ] Run Agent 1 re-audit → Get new score
- [ ] **Score:** ___/100 | **Gap:** ___ points

### Iteration 2 (if needed)
- [ ] Run Agent 2 with new feedback → Get more improvements
- [ ] Update translations/messages.en.yaml (iteration 2 changes)
- [ ] Run `make all`
- [ ] Extract new PDF, send to Agent 1
- [ ] **Score:** ___/100 | **Gap:** ___ points

### Iteration 3 (if needed)
- [ ] Run Agent 2 → Get final polish improvements
- [ ] Update translations/messages.en.yaml (iteration 3 changes)
- [ ] Run `make all`
- [ ] Extract new PDF, send to Agent 1
- [ ] **Score:** ___/100 | **Gap:** ___ points

### Final Status
- [ ] Score ≥95 ✅
- [ ] All audit reports saved
- [ ] YAML updated and committed
- [ ] PDF ready to use

---

## CV 2: romain-sickenberg-backend-fr

### Iteration 1
- [ ] Extract resume.json, PDF text, current messages.fr.yaml
- [ ] Run Agent 1 audit (use same process, French YAML)
- [ ] Save audit to: `output/audits/backend-fr-audit-v1.json`
- [ ] Run Agent 2 optimization
- [ ] Update translations/messages.fr.yaml
- [ ] Run `make all`
- [ ] Re-audit
- [ ] **Score:** ___/100 | **Gap:** ___ points

### Iteration 2-3 (as needed)
- [ ] Follow same pattern as CV 1
- [ ] **Final Score:** ___/100

### Final Status
- [ ] Score ≥95 ✅

---

## CV 3: romain-sickenberg-n1n2n3-en

### Iteration 1
- [ ] Extract resume.json (support variant), PDF text, messages.en.yaml
- [ ] Run Agent 1 audit
- [ ] Save audit to: `output/audits/n1n2n3-en-audit-v1.json`
- [ ] Run Agent 2 optimization
- [ ] Update translations/messages.en.yaml
- [ ] Run `make all`
- [ ] Re-audit
- [ ] **Score:** ___/100 | **Gap:** ___ points

### Iteration 2-3 (as needed)
- [ ] Follow same pattern
- [ ] **Final Score:** ___/100

### Final Status
- [ ] Score ≥95 ✅

---

## CV 4: romain-sickenberg-n1n2n3-fr

### Iteration 1
- [ ] Extract resume.json (support variant), PDF text, messages.fr.yaml
- [ ] Run Agent 1 audit
- [ ] Save audit to: `output/audits/n1n2n3-fr-audit-v1.json`
- [ ] Run Agent 2 optimization
- [ ] Update translations/messages.fr.yaml
- [ ] Run `make all`
- [ ] Re-audit
- [ ] **Score:** ___/100 | **Gap:** ___ points

### Iteration 2-3 (as needed)
- [ ] Follow same pattern
- [ ] **Final Score:** ___/100

### Final Status
- [ ] Score ≥95 ✅

---

## Summary Table

| CV | Initial | Final | Iterations | Status |
|----|---------|-------|------------|--------|
| backend-en | _ | _ | _ | ⏳ |
| backend-fr | _ | _ | _ | ⏳ |
| n1n2n3-en | _ | _ | _ | ⏳ |
| n1n2n3-fr | _ | _ | _ | ⏳ |

---

## Final Steps

- [ ] All 4 CVs score ≥95
- [ ] All audit reports saved to output/audits/
- [ ] All translation files updated (messages.en.yaml, messages.fr.yaml)
- [ ] All PDFs regenerated with `make all`
- [ ] Optimized PDFs copied to output/optimized/
- [ ] Backup updated YAML files
- [ ] Commit to git: `git add translations/messages.*.yaml output/audits/`
- [ ] Commit message: "feat: ATS optimize CVs to 95+ score across all variants"

---

## Notes & Learnings

### Common Improvements Across CVs
- Keyword 1: _____ (impact: ___%)
- Keyword 2: _____ (impact: ___%)
- Verb strengthening (impact: ___%)

### Language-Specific Patterns
- English pattern: _____
- French pattern: _____

### Role-Specific Patterns
- Backend pattern: _____
- Support pattern: _____

### Time Tracking
- CV 1 total time: ___ minutes
- CV 2 total time: ___ minutes
- CV 3 total time: ___ minutes
- CV 4 total time: ___ minutes
- **Grand total:** ___ minutes

---

## Success! 🎉

All 4 CVs optimized to ≥95 ATS score and ready for recruiters.

Generated: _________________
By: _________________

