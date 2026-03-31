#!/bin/bash

score=0
max_score=100

echo "=== LKS AUTO CHECK START ==="


################################
# Laravel structure
################################
if [ -f artisan ] && [ -d app ] && [ -d routes ]; then
echo "Laravel structure OK (+5)"
score=$((score+5))
fi


################################
# Sanctum full validation
################################
if grep -q HasApiTokens app/Models/User.php 2>/dev/null && \
grep -q sanctum config/auth.php 2>/dev/null && \
grep -R "auth:sanctum" routes 2>/dev/null; then
echo "Sanctum configured correctly (+10)"
score=$((score+10))
fi


################################
# UUID as PRIMARY KEY validation
################################
uuid_pk=$(grep -R "\$table->uuid('id')->primary()" database/migrations 2>/dev/null | wc -l)

if [ "$uuid_pk" -ge 2 ]; then
echo "UUID primary key detected (+10)"
score=$((score+10))
fi


################################
# SoftDeletes usage
################################
softdelete=$(grep -R "SoftDeletes" app/Models 2>/dev/null | wc -l)

if [ "$softdelete" -ge 2 ]; then
echo "SoftDeletes implemented (+8)"
score=$((score+8))
fi


################################
# Policy registered correctly
################################
if grep -R "Policies" app/Providers/AuthServiceProvider.php 2>/dev/null; then
echo "Policy registered (+8)"
score=$((score+8))
fi


################################
# Approval Flow schema validation
################################
if grep -R "approved_by" database/migrations >/dev/null 2>&1 && \
grep -R "reviewed_by" database/migrations >/dev/null 2>&1 && \
grep -R "status" database/migrations >/dev/null 2>&1; then
echo "Approval workflow schema OK (+10)"
score=$((score+10))
fi


################################
# approval_logs table validation
################################
if grep -R "approval_logs" database/migrations >/dev/null 2>&1; then
echo "Approval logs table detected (+5)"
score=$((score+5))
fi


################################
# Amortization formula detection
################################
if grep -R "monthly_installment" app >/dev/null 2>&1 && \
grep -R "pow(" app >/dev/null 2>&1; then
echo "Amortization formula detected (+12)"
score=$((score+12))
fi


################################
# Amortization schedule table
################################
if grep -R "remaining_balance" app >/dev/null 2>&1; then
echo "Amortization schedule detected (+5)"
score=$((score+5))
fi


################################
# REST API endpoints validation
################################
if grep -R "applications" routes/api.php >/dev/null 2>&1 && \
grep -R "review" routes/api.php >/dev/null 2>&1 && \
grep -R "approve" routes/api.php >/dev/null 2>&1; then
echo "REST API endpoints detected (+8)"
score=$((score+8))
fi


################################
# Dashboard stats endpoint
################################
if grep -R "dashboard" routes/api.php >/dev/null 2>&1; then
echo "Dashboard stats endpoint detected (+5)"
score=$((score+5))
fi


################################
# FormRequest validation
################################
if [ -d app/Http/Requests ] && [ "$(ls -A app/Http/Requests)" ]; then
echo "FormRequest validation detected (+5)"
score=$((score+5))
fi


################################
# Multi-parameter filtering
################################
if grep -R "filter" app >/dev/null 2>&1 && \
grep -R "where" app >/dev/null 2>&1; then
echo "Filtering logic detected (+5)"
score=$((score+5))
fi


################################
# Dashboard chart detection
################################
if grep -R "chart" resources 2>/dev/null; then
echo "Dashboard chart detected (+4)"
score=$((score+4))
fi


################################
# Dynamic interest tier logic
################################
if grep -R "interest_rate" app >/dev/null 2>&1 && \
grep -R "omzet" app >/dev/null 2>&1; then
echo "Dynamic interest logic detected (+5)"
score=$((score+5))
fi


echo "==============================="
echo "FINAL SCORE: $score / $max_score"
echo "==============================="
echo "=== LKS AUTO CHECK END ==="
