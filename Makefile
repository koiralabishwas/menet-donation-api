.PHONY: help
help: ## help 表示 `make help` でタスクの一覧を確認できます
	@echo "------- タスク一覧 ------"
	@grep -E '^[0-9a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36mmake %-20s\033[0m %s\n", $$1, $$2}'

.PHONY: install
install: ## インストールします
	@npm install
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
	@make clear-logs
	@php artisan test
	@#make test-webhook-payment-intent
	@rm -rf .phpunit.cache .phpunit.result.cache

.PHONY: format-code
format-code: ## コードをフォーマットします
	@./vendor/bin/pint

.PHONY: list-routes
list-routes: ## ルートを一覧表示します
	@php artisan route:list

.PHONY: clear
clear: ## キャッシュをクリアします
	@php artisan cache:clear
	@php artisan config:clear
	@php artisan route:clear
	@php artisan view:clear

.PHONY: clear-logs
clear-logs: ## storage/logs/laravel.logのログをクリアします
	@echo "" > storage/logs/laravel.log

.PHONY: show-logs
show-logs: ## show laravel.log
	@echo "!!laravel-Logs!! => check for error"
	@cat storage/logs/laravel.log

.PHONY: run-server-with-webhook
run-server-webhook:
	php artisan serve \
	& stripe listen --forward-to localhost:8000/api/webhooks/customer-subscription-created --events=customer.subscription.created \
	& stripe listen --forward-to localhost:8000/api/webhooks/customer-subscription-updated --events=customer.subscription.updated \
	& stripe listen --forward-to localhost:8000/api/webhooks/invoice-paid --events=invoice.paid
#	& stripe listen --forward-to localhost:8000/api/webhooks/payment-intent-succeed --events=payment_intent.succeeded \

.PHONY: run-server
run-server: ## アプリを実行します
	@php artisan serve

