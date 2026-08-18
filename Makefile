# Executables (local)
BUN_EXEC = bun
PHP_EXEC = php

# Misc
.DEFAULT_GOAL = help
.PHONY        : help

## —— 🎵 🐳 The Makefile 🐳 🎵 ——————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9\./_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

backend:
	@cd theme/jsonresume-theme-developer-ats && $(BUN_EXEC) run build
	@$(BUN_EXEC) i
	@$(PHP_EXEC) entrypoint.php > resume.json && bunx resuml render -t jsonresume-theme-developer-ats -r resume.json