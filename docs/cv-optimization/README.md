# CV Optimization Agent System

This directory contains the complete documentation and implementation guide for the AI-powered CV optimization system.

## 📚 Documentation Files

### 1. **cv-optimization-system.md** (Full Architecture)
- Complete system design and philosophy
- 8-dimension ATS scoring system detailed breakdown
- Workflow diagrams and implementation phases
- Agent handoff protocols
- Success criteria and integration notes

**When to use:** Understanding the full system, designing improvements, reviewing approach

### 2. **CV_OPTIMIZATION_AGENTS.md** (Agent Prompts)
- Complete System Role for both agents
- ATS Reviewer scoring instructions with examples
- CV Generator optimization strategy
- Output format specifications (JSON/Markdown)
- Key principles and best practices
- Feedback loop protocol

**When to use:** Running agents in Claude conversations, copy-paste these prompts

### 3. **QUICK_START_GUIDE.md** (Step-by-Step Execution)
- One-time setup instructions
- Workflow for running one CV through the loop
- Bash commands for PDF extraction
- Tracking table for progress
- Expected timeline (30-45 min per CV)
- Troubleshooting tips
- Automation scripts

**When to use:** Actually executing the optimization process, following step-by-step

### 4. **ATS_KEYWORD_DATABASE.md** (Keyword Reference)
- Tier 1-3 keywords for backend engineering
- Tier 1-3 keywords for support engineering
- Keywords to avoid/de-emphasize
- Keyword placement strategies
- Metrics & quantification guidelines
- Action verb reference library
- Language-specific terminology (FR/EN)
- Scoring validation checklist

**When to use:** Validating keyword choices, generating suggestions, checking quality

## 🚀 Quick Start

1. **Read:** Start with `cv-optimization-system.md`
2. **Setup:** Follow installation steps in `QUICK_START_GUIDE.md`
3. **Execute:** Use `QUICK_START_GUIDE.md` for step-by-step workflow
4. **Copy Prompts:** Paste agent prompts from `CV_OPTIMIZATION_AGENTS.md` into Claude
5. **Reference:** Use `ATS_KEYWORD_DATABASE.md` for keyword validation

## 📊 System Overview

**Two Agents in Feedback Loop:**
- **Agent 1:** ATS Reviewer & Validator → Audits CV, provides scored feedback
- **Agent 2:** CV Content Generator & Optimizer → Improves CV based on feedback

**Typical Flow:**
```
Start → Extract PDF text → Reviewer audits (Score: 78)
→ Generator improves → Re-audit (Score: 87)
→ Generator refines → Re-audit (Score: 95)
→ PUBLISH
```

**Target:** ATS Score ≥ 95/100 per CV variant

**CVs to Optimize:**
- romain-sickenberg-backend-en.pdf
- romain-sickenberg-backend-fr.pdf
- romain-sickenberg-n1n2n3-en.pdf
- romain-sickenberg-n1n2n3-fr.pdf

## 📈 Expected Results

- Baseline Score: 75-80
- After Iteration 1: 82-87
- After Iteration 2: 88-92
- After Iteration 3: 92-96
- Final Target: 95+

**Time per CV:** 30-45 minutes (2-3 iterations)
**All 4 CVs:** 2-3 hours total

## 🎯 Success Criteria

✅ All 4 CV variants score ≥ 95/100
✅ No more than 3-4 iterations per CV
✅ Consistent improvement trajectory (+5-10 points/iteration)
✅ All changes authentic and defensible
✅ Ready for ATS + recruiter impact

## 📁 Output Structure

After optimization:
```
output/
├── optimized/
│   ├── romain-sickenberg-backend-en-v2.pdf
│   ├── romain-sickenberg-backend-fr-v2.pdf
│   ├── romain-sickenberg-n1n2n3-en-v2.pdf
│   └── romain-sickenberg-n1n2n3-fr-v2.pdf
├── audits/
│   ├── backend-en-audit-v1.json
│   ├── backend-en-audit-v2.json
│   └── ... (audit reports for each iteration)
```

## 🔑 Key Principles

1. **Authenticity First** - Never invent achievements
2. **Natural Integration** - Keywords should read naturally
3. **Metrics Are Gold** - Every metric from real projects
4. **Iterative** - Each change maps to audit feedback
5. **Language Specific** - Adapt for French/English norms

## 🆘 Support

- **Scoring not improving?** Check if generator implemented top fixes
- **Keyword stuffing?** Ask reviewer to check keyword density
- **Formatting breaks?** Use clean markdown, no tables/images
- **Language issues?** Verify French/English terminology norms

---

**Status:** Ready for implementation
**Last Updated:** August 26, 2026

