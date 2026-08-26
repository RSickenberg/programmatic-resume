# Executables (local)
BUN_EXEC      = bun
PHP_EXEC      = php
COMPOSER_EXEC = composer

# Misc
.DEFAULT_GOAL = help
OUTPUT_DIR    = output
.PHONY        : help deps update-deps lint-translations backend backend-fr support-n1n2n3 support-n1n2n3-fr backends supports all

## —— 🎵 🐳 The Makefile 🐳 🎵 ——————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9\./_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

update-deps: ## Update Composer and Bun dependencies, at the root and in the theme package
	@echo "==> composer (root)"
	@$(COMPOSER_EXEC) update
	@echo "==> bun (root)"
	@$(BUN_EXEC) update
	@echo "==> bun (theme)"
	@cd theme/jsonresume-theme-developer-ats && $(BUN_EXEC) update
	@echo "Dependencies updated. Run 'make all' to rebuild the theme and regenerate every CV."

lint-translations: ## Check locale parity and catch bold spans that lose their space in the PDF
	@$(PHP_EXEC) bin/check-translations.php

deps: lint-translations ## Prepare the output dir, install JS deps and build the theme package
	@mkdir -p $(OUTPUT_DIR)
	@cd theme/jsonresume-theme-developer-ats && $(BUN_EXEC) run build
	@$(BUN_EXEC) i

backend: deps ## Generate the backend-dev resume (English)
	@$(PHP_EXEC) entrypoint.php backend-dev --locale en -o $(OUTPUT_DIR)/en/resume.json
	@bunx resuml render -t jsonresume-theme-developer-ats -r $(OUTPUT_DIR)/en/resume.json --language en -o $(OUTPUT_DIR)/en/resume.html
	@bunx resuml pdf -t jsonresume-theme-developer-ats -r $(OUTPUT_DIR)/en/resume.json --language en -o $(OUTPUT_DIR)/romain-sickenberg-backend-en.pdf

backend-fr: deps ## Generate the backend-dev resume (French)
	@$(PHP_EXEC) entrypoint.php backend-dev --locale fr -o $(OUTPUT_DIR)/fr/resume.fr.json
	@bunx resuml render -t jsonresume-theme-developer-ats -r $(OUTPUT_DIR)/fr/resume.fr.json --language fr -o $(OUTPUT_DIR)/fr/resume.fr.html
	@bunx resuml pdf -t jsonresume-theme-developer-ats -r $(OUTPUT_DIR)/fr/resume.fr.json --language fr -o $(OUTPUT_DIR)/romain-sickenberg-backend-fr.pdf

support-n1n2n3: deps ## Generate the support-n1n2n3 resume (English)
	@$(PHP_EXEC) entrypoint.php support-n1n2n3 --locale en -o $(OUTPUT_DIR)/en/support-n1n2n3.json
	@bunx resuml render -t jsonresume-theme-developer-ats -r $(OUTPUT_DIR)/en/support-n1n2n3.json --language en -o $(OUTPUT_DIR)/en/support-n1n2n3.html
	@bunx resuml pdf -t jsonresume-theme-developer-ats -r $(OUTPUT_DIR)/en/support-n1n2n3.json --language en -o $(OUTPUT_DIR)/romain-sickenberg-n1n2n3-en.pdf

support-n1n2n3-fr: deps ## Generate the support-n1n2n3 resume (French)
	@$(PHP_EXEC) entrypoint.php support-n1n2n3 --locale fr -o $(OUTPUT_DIR)/fr/support-n1n2n3.fr.json
	@bunx resuml render -t jsonresume-theme-developer-ats -r $(OUTPUT_DIR)/fr/support-n1n2n3.fr.json --language fr -o $(OUTPUT_DIR)/fr/support-n1n2n3.fr.html
	@bunx resuml pdf -t jsonresume-theme-developer-ats -r $(OUTPUT_DIR)/fr/support-n1n2n3.fr.json --language fr -o $(OUTPUT_DIR)/romain-sickenberg-n1n2n3-fr.pdf


backends: backend backend-fr ## Generate all backend-dev variants (en, fr)

supports: support-n1n2n3 support-n1n2n3-fr ## Generate all support-n1n2n3 variants (en, fr)

all: backends supports ## Generate every profile, every locale
