.PHONY: help
help: ## help 表示 `make help` でタスクの一覧を確認できます
	@echo "------- タスク一覧 ------"
	@grep -E '^[0-9a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36mmake %-20s\033[0m %s\n", $$1, $$2}'

.PHONY: install
install: ## インストールします
	@composer install

.PHONY: update
update: ## アップデートします
	@composer update

.PHONY: swagger
swagger: ## swaggerを生成します
	@php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"
	@php artisan l5-swagger:generate

.PHONY: test
test: ## テストを実行します
	@php artisan test
	@rm -rf .phpunit.cache .phpunit.result.cache

.PHONY: format-code
format-code: ## コードをフォーマットします
	@./vendor/bin/pint

.PHONY: list-routes
list-routes: ## ルートを一覧表示します
	@php artisan route:list

.PHONY: clear-logs
clear-logs: ## storage/logs/laravel.logのログをクリアします
	@echo "" > storage/logs/laravel.log

.PHONY: run-server
run-server: ## アプリを実行します
	@php artisan serve

.PHONY: run-webhook
run-webhook: ## webhook を　実行
	@stripe listen --forward-to localhost:8000/api/webhook
