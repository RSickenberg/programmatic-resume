# Executables (local)
BUN_EXEC = bun
PHP_EXEC = php

# Misc
.DEFAULT_GOAL = help
OUTPUT_DIR    = output
.PHONY        : help deps backend backend-fr support-n1n2 support-n1n2-fr backends supports all

## —— 🎵 🐳 The Makefile 🐳 🎵 ——————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9\./_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

deps: ## Prepare the output dir, install JS deps and build the theme package
	@mkdir -p $(OUTPUT_DIR)
	@cd theme/jsonresume-theme-developer-ats && $(BUN_EXEC) run build
	@$(BUN_EXEC) i

backend: deps ## Generate the backend-dev resume (English)
	@$(PHP_EXEC) entrypoint.php backend-dev --locale en -o $(OUTPUT_DIR)/resume.json
	@bunx resuml render -t jsonresume-theme-developer-ats -r $(OUTPUT_DIR)/resume.json --language en -o $(OUTPUT_DIR)/resume.html
	@bunx resuml pdf -t jsonresume-theme-developer-ats -r $(OUTPUT_DIR)/resume.json --language en -o $(OUTPUT_DIR)/resume.pdf

backend-fr: deps ## Generate the backend-dev resume (French)
	@$(PHP_EXEC) entrypoint.php backend-dev --locale fr -o $(OUTPUT_DIR)/resume.fr.json
	@bunx resuml render -t jsonresume-theme-developer-ats -r $(OUTPUT_DIR)/resume.fr.json --language fr -o $(OUTPUT_DIR)/resume.fr.html
	@bunx resuml pdf -t jsonresume-theme-developer-ats -r $(OUTPUT_DIR)/resume.fr.json --language fr -o $(OUTPUT_DIR)/resume.fr.pdf

support-n1n2: deps ## Generate the support-n1n2 resume (English)
	@$(PHP_EXEC) entrypoint.php support-n1n2 --locale en -o $(OUTPUT_DIR)/support-n1n2.json
	@bunx resuml render -t jsonresume-theme-developer-ats -r $(OUTPUT_DIR)/support-n1n2.json --language en -o $(OUTPUT_DIR)/support-n1n2.html
	@bunx resuml pdf -t jsonresume-theme-developer-ats -r $(OUTPUT_DIR)/support-n1n2.json --language en -o $(OUTPUT_DIR)/support-n1n2.pdf

support-n1n2-fr: deps ## Generate the support-n1n2 resume (French)
	@$(PHP_EXEC) entrypoint.php support-n1n2 --locale fr -o $(OUTPUT_DIR)/support-n1n2.fr.json
	@bunx resuml render -t jsonresume-theme-developer-ats -r $(OUTPUT_DIR)/support-n1n2.fr.json --language fr -o $(OUTPUT_DIR)/support-n1n2.fr.html
	@bunx resuml pdf -t jsonresume-theme-developer-ats -r $(OUTPUT_DIR)/support-n1n2.fr.json --language fr -o $(OUTPUT_DIR)/support-n1n2.fr.pdf


backends: backend backend-fr ## Generate all backend-dev variants (en, fr)

supports: support-n1n2 support-n1n2-fr ## Generate all support-n1n2 variants (en, fr)

all: backends supports ## Generate every profile, every locale
