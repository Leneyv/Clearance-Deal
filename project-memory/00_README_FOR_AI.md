# CLEARANCE KENYA PROJECT MEMORY — README FOR AI AGENTS & SUPERVISORS

> **CRITICAL DIRECTIVE FOR ALL AI AGENTS (Claude, ChatGPT, Gemini, Codex, etc.):**
> This `/project-memory` directory is the authoritative, living operational log and handover bridge for **Clearance Kenya** (`clearance.co.ke`). You MUST read this before beginning any task, update it before concluding your turn, and maintain exact timestamps, file paths, and testing statuses.

---

## 1. 10-Line Executive Summary of Clearance Kenya
1. **Entity & Mission:** Clearance Kenya (`https://clearance.co.ke`) is a premier e-commerce liquidation and overstock clearinghouse based in Nairobi, Kenya.
2. **Core Specialization:** 100% human hair wigs, raw virgin hair, HD lace frontals, 360 weaves, hair tools, and authentic liquidated consumer electronics.
3. **Core Financing (BNPL):** Offers "Lipa Polepole" financing via custom plugin `clearance-pay-later` (50% upfront payment, 12 monthly instalments).
4. **Primary Payments:** Integrated with **PesaPal v3** gateway supporting M-Pesa STK push, Airtel Money, Visa, and Mastercard in KSh (Kenyan Shilling).
5. **Strict Checkout Policy:** **Guest checkout is strictly disabled** (mandatory account registration); **Cash on Delivery (COD) is permanently disabled**; only paid doorstep delivery across Kenya is permitted.
6. **Active Promo:** Store-wide 10% discount promo code `CLEARANCE10` (max 100 uses, unlimited duration).
7. **SEO & AEO Engine:** Custom engine (`inc/seo-aeo.php`, `llms.txt`, `robots.txt`) ranking for high-intent Kenyan search queries targeting `"Price in Kenya"`.
8. **Content Hub:** 100 evergreen Kenyan hair guides and buyer tutorials published without visible publish dates to preserve perpetual authority.
9. **Tech Stack & Hosting:** Ubuntu 24.04 LTS VPS (`129.121.132.35`), Nginx 1.24, PHP 8.3-FPM, MySQL 8.0, WordPress 6.x, WooCommerce 11.x, custom theme `clearance-deal`.
10. **Zero-Fiction Rule:** All features must be tested against live PHP/MySQL environments; no guessing, no plaintext secrets, and unverified items must be explicitly marked `UNVERIFIED` or `NOT TESTED`.

---

## 2. File Index & Purpose

| File | Purpose |
|---|---|
| [`00_README_FOR_AI.md`](./00_README_FOR_AI.md) | Entry point, 10-line executive summary, navigation, and rules of engagement. |
| [`01_PROJECT_OVERVIEW.md`](./01_PROJECT_OVERVIEW.md) | Business goals, target audience, stack versions, domains, and roles. |
| [`02_COMMAND_LOG.md`](./02_COMMAND_LOG.md) | Sequential history of every user instruction (CMD-001 through CMD-015+). |
| [`03_CHANGELOG.md`](./03_CHANGELOG.md) | Detailed code and config changes (CHG-001 through CHG-015+) with rollback plans. |
| [`04_FEATURES_STATUS.md`](./04_FEATURES_STATUS.md) | Comprehensive feature matrix with verified implementation and test statuses. |
| [`05_ARCHITECTURE.md`](./05_ARCHITECTURE.md) | Directory structure, file roles, theme hierarchy, and visual data flow diagrams. |
| [`06_DATABASE_AND_APIS.md`](./06_DATABASE_AND_APIS.md) | Database tables, options keys, external APIs (PesaPal v3), and integrations. |
| [`07_ENV_AND_SETUP.md`](./07_ENV_AND_SETUP.md) | Build, deployment, and runtime guide with variable descriptions (NO SECRETS). |
| [`08_DECISIONS.md`](./08_DECISIONS.md) | Architecture Decision Records (ADRs) explaining choices and rejected options. |
| [`09_KNOWN_ISSUES_AND_TODO.md`](./09_KNOWN_ISSUES_AND_TODO.md) | Bugs, technical debt, shortcuts taken, and prioritized future roadmap. |
| [`10_QC_CHECKLIST.md`](./10_QC_CHECKLIST.md) | Step-by-step test suites for authentication, billing, forms, mobile, and SEO. |
| [`11_AGENT_AND_PROMPTS.md`](./11_AGENT_AND_PROMPTS.md) | Documentation on automation scripts, tools, and guardrails used by AI agents. |

---

## 3. Maintenance Rules for Succeeding AI Agents
- **Update Before Answering:** Do NOT conclude a conversation or declare a task complete without updating this memory.
- **Append Only for Logs:** In `02_COMMAND_LOG.md` and `03_CHANGELOG.md`, always append new entries at the bottom. Never rewrite or delete historical entries.
- **Truth in Testing:** Only mark a feature "tested" if you ran the test command and verified the exit code/output. Mark unverified work as `NOT TESTED` or `UNVERIFIED`.
- **Absolute Secrets Prohibition:** Never record raw passwords, private keys, or API credentials in any file inside `/project-memory`.
