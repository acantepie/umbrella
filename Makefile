COMPOSER      = composer
YARN          = yarn
PHP_CS_FIXER  = ./vendor/bin/php-cs-fixer
PSALM         = ./vendor/bin/psalm
PHPSTAN       = ./vendor/bin/phpstan
PHPUNIT       = ./vendor/bin/simple-phpunit

help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

fix-php: ## Fix files with php-cs-fixer
	$(PHP_CS_FIXER) fix

fix-js: ## Fix files with eslint
	$(YARN) lint-fix

fix-all: fix-php fix-js ## Fix all files

analyse: ## Run php analyser
	$(PSALM)
	$(PHPSTAN)

test: ## Run php unit
	$(PHPUNIT)

check: fix-all analyse test

doc: ## Serve docsify
	yarn docsify serve docs

doc-update-config: ## Update config-ref of documentation
	bin/update-doc-config


