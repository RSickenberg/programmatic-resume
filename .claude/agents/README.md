# CV Optimization Agents

Two-agent system for optimizing CVs to ATS score ≥95/100.

**Key difference from standard approach:** 
- Work with PHP code + YAML translations (source of truth)
- Agents recommend changes to translation strings
- Run `make all` to regenerate final PDFs
- Re-audit to validate improvements

## Agent 1: ATS Reviewer

Audits PDFs and recommends specific changes to YAML translation strings.

**Input:** Current PDF + resume.json source data  
**Output:** ATS score, 8-dimension breakdown, ranked issues with YAML string references

## Agent 2: CV Content Optimizer

Takes reviewer feedback and generates improved YAML translation values.

**Input:** ATS audit feedback + current messages.en/fr.yaml  
**Output:** Updated YAML values to paste into translation files

## Workflow

```
1. Extract current resume.json
2. Run Reviewer Agent → Get audit + recommendations
3. Run Optimizer Agent → Get improved YAML values
4. Update translations/messages.en.yaml and messages.fr.yaml
5. Run: make all
6. Re-audit new PDFs with Reviewer Agent
7. Loop until score ≥95
```

## Files

- `AGENT_REVIEWER.md` — ATS Reviewer system role and instructions
- `AGENT_OPTIMIZER.md` — CV Content Optimizer system role and instructions
- `WORKFLOW.md` — Step-by-step execution guide
- `YAML_MAPPING.md` — How resume structure maps to YAML keys

## Quick Start

1. Read `WORKFLOW.md`
2. Copy Agent 1 prompt from `AGENT_REVIEWER.md` into Claude
3. Paste current PDF + resume.json
4. Get audit feedback + YAML recommendations
5. Copy Agent 2 prompt from `AGENT_OPTIMIZER.md`
6. Get improved YAML values
7. Update translation files
8. Run `make all`
9. Re-audit and loop

Total time: 30-45 min per CV × 4 CVs = 2-3 hours

