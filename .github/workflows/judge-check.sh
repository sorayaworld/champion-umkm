#!/bin/bash

score=0

echo "=== LKS AUTO CHECK START ==="

[ -f artisan ] && echo "Laravel OK (+5)" && score=$((score+5))

grep -q HasApiTokens app/Models/User.php && \
echo "Sanctum OK (+10)" && score=$((score+10))

grep -R "uuid(" database/migrations >/dev/null && \
echo "UUID OK (+10)" && score=$((score+10))

grep -R "SoftDeletes" app/Models >/dev/null && \
echo "SoftDelete OK (+10)" && score=$((score+10))

[ -d app/Policies ] && \
echo "Policy OK (+10)" && score=$((score+10))

grep -R "approved_by" database/migrations >/dev/null && \
echo "Approval Flow OK (+15)" && score=$((score+15))

grep -R "monthly_installment" app >/dev/null && \
echo "Amortization OK (+20)" && score=$((score+20))

grep -R "dashboard" routes >/dev/null && \
echo "Dashboard OK (+10)" && score=$((score+10))

echo "FINAL SCORE = $score / 100"
