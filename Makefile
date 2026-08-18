# Executables (local)
BUN_EXEC = bun
PHP_EXEC = php

# Misc
.DEFAULT_GOAL = help
.PHONY        : help

## —— 🎵 🐳 The Makefile 🐳 🎵 ——————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9\./_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

backend: ## Generate the backend-dev resume (English)
	@cd theme/jsonresume-theme-developer-ats && $(BUN_EXEC) run build
	@$(BUN_EXEC) i
	@$(PHP_EXEC) entrypoint.php backend-dev --locale en -o resume.json
	@bunx resuml render -t jsonresume-theme-developer-ats -r resume.json --language en -o resume.html
	@bunx resuml pdf -t jsonresume-theme-developer-ats -r resume.json --language en -o resume.pdf

backend-fr: ## Generate the backend-dev resume (French)
	@cd theme/jsonresume-theme-developer-ats && $(BUN_EXEC) run build
	@$(BUN_EXEC) i
	@$(PHP_EXEC) entrypoint.php backend-dev --locale fr -o resume.fr.json
	@bunx resuml render -t jsonresume-theme-developer-ats -r resume.fr.json --language fr -o resume.fr.html
	@bunx resuml pdf -t jsonresume-theme-developer-ats -r resume.fr.json --language fr -o resume.fr.pdf