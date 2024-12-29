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
	& stripe listen --forward-to localhost:8000/api/webhooks/payment-intent-succeed --events=payment_intent.succeeded \
	& stripe listen --forward-to localhost:8000/api/webhooks/customer-subscription-created --events=customer.subscription.created \
	& stripe listen --forward-to localhost:8000/api/webhooks/invoice-paid --events=invoice.paid \
	& stripe listen --forward-to localhost:8000/api/webhooks/customer-subscription-deleted --events=customer.subscription.deleted

.PHONY: run-server
run-server: ## アプリを実行します
	@php artisan serve

.PHONY: run-webhook
run-webhook: ## webhook を　実行
	@stripe listen --forward-to localhost:8000/api/webhook

#.PHONY : test-webhook-payment-intent
#test-webhook-payment-intent: ## payment-intentのwebhook テストを実行
#	@stripe trigger payment_intent.succeeded \
#      --override payment_intent:amount=3000 \
#      --override payment_intent:currency="jpy" \
#      --override payment_intent:receipt_email="test@example.com" \
#      --add payment_intent:metadata.payment_schedule="ONE_TIME" \
#      --add payment_intent:metadata.donor_name="test" \
#      --add payment_intent:metadata.currency="jpy" \
#      --add payment_intent:metadata.donation_project="高校進学ガイダンス" \
#      --add payment_intent:metadata.donor_id="1" \
#      --add payment_intent:metadata.amount="3000" \
#      --add payment_intent:metadata.donor_external_id="6c9040d0-6f78-4d0b-aa08-60092cc674ac" \
