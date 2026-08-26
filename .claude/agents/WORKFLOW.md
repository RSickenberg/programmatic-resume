# CV Optimization Workflow

Complete step-by-step guide using the two-agent system with PHP/YAML source.

## Overview

```
Extract resume.json + current YAML
        ↓
[AGENT 1] Audit PDF → Score & YAML recommendations
        ↓
[AGENT 2] Generate improved YAML values
        ↓
Update translations/messages.*.yaml
        ↓
Run: make all
        ↓
[AGENT 1] Re-audit new PDF
        ↓
Score ≥95? → PUBLISH / Score <95? → Loop
```

**Time per CV:** 30-45 minutes (2-3 iterations)
**All 4 CVs:** 2-3 hours total

## Phase 1: One-Time Setup

```bash
cd ~/Projects/haux49/programmatic-resume

# Install PDF text extraction
pip install pdfplumber --break-system-packages

# Create working directories
mkdir -p output/optimized output/audits
```

## Phase 2: First CV (backend-en)

### Step 1: Extract Current State

```bash
# Get resume.json
cat output/en/resume.json

# Extract PDF text
python3 << 'PYEOF'
import pdfplumber
with pdfplumber.open("output/romain-sickenberg-backend-en.pdf") as pdf:
    print("\n".join([page.extract_text() for page in pdf.pages]))
PYEOF

# View current YAML
cat translations/messages.en.yaml
```

Copy all three: resume.json, PDF text, and current YAML

### Step 2: Run Agent 1 (Reviewer)

**In Claude:**
1. Paste Agent 1 prompt from `AGENT_REVIEWER.md`
2. Provide: PDF text + resume.json + messages.en.yaml
3. Get back: JSON audit with YAML key recommendations

**Save the audit:**
```bash
cat > output/audits/backend-en-audit-v1.json << 'EOF'
[PASTE AUDIT JSON FROM CLAUDE]
EOF
```

### Step 3: Run Agent 2 (Optimizer)

**In Claude:**
1. Paste Agent 2 prompt from `AGENT_OPTIMIZER.md`
2. Provide: Audit JSON + messages.en.yaml
3. Get back: Ready-to-paste improved YAML values

### Step 4: Update Translation File

```bash
# Backup
cp translations/messages.en.yaml translations/messages.en.yaml.backup

# Edit with improved values
nano translations/messages.en.yaml
```

Find each YAML key from Agent 2 and replace its value.

### Step 5: Regenerate & Re-Audit

```bash
# Regenerate all PDFs with new translations
make all

# Extract new PDF text
python3 << 'PYEOF'
import pdfplumber
with pdfplumber.open("output/romain-sickenberg-backend-en.pdf") as pdf:
    print("\n".join([page.extract_text() for page in pdf.pages]))
PYEOF

# Send new text back to Agent 1 for re-audit
```

### Step 6: Decision

- **Score ≥95:** Done! ✅
- **Score 85-94:** Run Agent 2 again (Iteration 2)
- **Score <85:** Investigate with Agent 1

## Phase 3: Remaining 3 CVs

Repeat the same workflow for:
- backend-fr (French version) → Use messages.fr.yaml
- n1n2n3-en (Support role) → Use messages.en.yaml
- n1n2n3-fr (Support FR) → Use messages.fr.yaml

**Can parallelize:**
- Extract all 4 PDFs
- Run all 4 Agent 1 audits in parallel
- Run all 4 Agent 2 optimizations in parallel
- Update both YAML files
- Run `make all` once
- Re-audit all 4 in parallel

Time: ~1.5 hours instead of 3 hours

## Phase 4: Validation

```bash
# Verify all 4 CVs at ≥95 score
# Commit optimized translations
git add translations/messages.*.yaml
git add output/audits/
git commit -m "feat: ATS optimize CVs to 95+ score"
```

## Troubleshooting

**PDF doesn't update:**
```bash
rm -rf output/*.pdf
make all
```

**YAML syntax error:**
```bash
python3 -c "import yaml; yaml.safe_load(open('translations/messages.en.yaml'))"
cp translations/messages.en.yaml.backup translations/messages.en.yaml  # Restore if needed
```

**Score went down:**
Ask Agent 1 to identify regressions and revert problematic YAML values.

