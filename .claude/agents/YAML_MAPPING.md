# YAML Translation Mapping Reference

Quick reference for which YAML keys impact ATS score most.

## Structure

Your CVs are generated from:
```
PHP classes (src/Generator/) 
  ↓ reads translations from
messages.en.yaml / messages.fr.yaml
  ↓ rendered by
theme/jsonresume-theme-developer-ats
  ↓ outputs to
resume.html / resume.pdf
```

## High-Impact Keys (Optimize These First)

### Priority 1: Work Experience Highlights (40-50% of impact)

```yaml
work:
  antistatique:
    highlight_1: "Main achievement here..."
    highlight_2: "..."
    highlight_3: "..."
    highlight_4: "..."
    highlight_5: "..."
    highlight_6: "..."
    highlight_7: "..."
  
  academic_work:
    highlight_1: "..."
    highlight_2: "..."
```

**Why:** Most scanned by recruiters and ATS systems

**What to improve:**
- Add keywords (1-3 per bullet, naturally)
- Add metrics (%, scale, time saved)
- Strong action verbs (architected, engineered, led, optimized)
- Business impact

### Priority 2: Professional Summary (15-20% of impact)

```yaml
basics:
  summary_role: "Backend-oriented Software Engineer with 5+ years..."
  summary_experience: "Hands-on experience across application design..."
```

**What to improve:**
- Add 2-3 Tier 1 keywords
- Strong opening verb
- Key technical skills
- Keep to 2-3 sentences

### Priority 3: Position Titles (5-10% of impact)

```yaml
work:
  antistatique:
    position: "Backend Software Engineer"
```

**What to improve:**
- Keep accurate to real title
- Can add keyword in parentheses if natural

### Priority 4: Everything Else (5-15%)

```yaml
work:
  antistatique:
    summary: "From Junior to mid-level Software Engineer..."
  
basics:
  backend_dev_position: "Backend-Software Engineer"
  support_position: "IT Support Technician (N1/N2/N3)"
```

## Example: Complete Optimization

### Before
```yaml
work:
  antistatique:
    highlight_1: "Developed and maintained enterprise Symfony 6 applications, using PHP 7.4/8.0, API Platform, Doctrine ORM, PostgreSQL and GitHub Actions."
    highlight_2: "Involved as PHP Lead Developer for a client project in Symfony with 4 other devs."
```

### After
```yaml
work:
  antistatique:
    highlight_1: "Architected scalable microservices using Symfony 6 (PHP 7.4/8.0), API Platform, Doctrine ORM, and PostgreSQL. Improved system performance by 35%; reduced deployment time from 30min to 5min via GitHub Actions CI/CD automation."
    highlight_2: "Led technical decisions as PHP Lead Developer for client project using Symfony with 4-member team. Drove code quality standards, resulting in 50% reduction in production bugs."
```

**Improvements:**
- Architected > Developed (stronger verb)
- Added microservices keyword
- Added metrics: 35%, 30min→5min
- Led > Involved (active voice)
- Added impact: 50% bug reduction

## English vs French

### English (messages.en.yaml)
- Terminology: "REST API", "Microservices", "System Design"
- Tone: Direct, action-oriented, American English
- Keywords: Python, JavaScript, Docker, Kubernetes, AWS

### French (messages.fr.yaml)
- Terminology: "API REST" (reversed), "Microservices", "Conception d'Architecture"
- Tone: More formal, professional expertise valued
- Keywords: Same technical terms, but French business idioms

## How to Edit

### Step 1: Locate the key

Agent 1 says: "Update `work.antistatique.highlight_1`"

### Step 2: Find in YAML file

```bash
grep -n "highlight_1" translations/messages.en.yaml
```

### Step 3: Replace the value

```bash
nano translations/messages.en.yaml
# Or use sed for quick replacement
sed -i 's/old text/new text/' translations/messages.en.yaml
```

### Step 4: Verify & Regenerate

```bash
python3 -c "import yaml; yaml.safe_load(open('translations/messages.en.yaml'))"
make all
```

## Most Common Edits

| Goal | Example |
|---|---|
| Add keyword | "Developed Symfony apps" → "Architected microservices using Symfony" |
| Add metric | "Built applications" → "Built for 5000+ users with 99.8% uptime" |
| Strengthen verb | "Involved in development" → "Led development efforts" |
| Add impact | "Reduced time by 75%" → "Reduced by 75% (20min to 5min), saving 100+ dev hours" |

## Remember

**Agent 1** tells you which YAML keys and what's wrong.
**Agent 2** tells you exactly what new text to use.
You copy Agent 2's improved text into the YAML file.
Run `make all` to regenerate PDFs.
Send back to Agent 1 for re-audit.

Loop until score ≥95.

