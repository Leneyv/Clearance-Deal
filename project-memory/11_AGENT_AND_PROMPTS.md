# 11. AI AGENTS, AUTOMATION SCRIPTS & PROMPT DIRECTIVES

This document records the automation scripts, LLM directives, and guidelines governing AI agents (such as Antigravity, Claude, ChatGPT, or custom automated workers) operating on the **Clearance Kenya** project.

---

## 1. Automation Scripts in Project

### A. 100 Kenyan SEO Blogs Generator (`generate_100_blogs.py`)
- **Location:** `/var/www/clearance/generate_100_blogs.py`
- **Purpose:** Programmatically generates 100 comprehensive, search-optimized blog articles for the Kenyan hair and wig market and publishes them into WordPress via WP-CLI / WordPress Database functions.
- **Topical Clusters Covered:**
  1. `prices-kenya`: Focuses specifically on cost breakdowns, comparisons, and purchasing advice across Nairobi and upcountry.
  2. `wigs-lace-frontals`: HD lace frontals, 13x4 / 13x6 frontals, transparent lace, and closure wigs.
  3. `raw-virgin-hair`: Raw Cambodian, Vietnamese, Brazilian, and Peruvian hair bundle distinctions.
  4. `shopping-guides-kenya`: How to buy wigs safely in Kenya, courier delivery tips, and avoiding salon markups.
  5. `maintenance-styling`: Washing, detangling, heat protection, and preserving lace lifespan in the Kenyan climate.
  6. `lipa-polepole-deals`: Explaining 50% upfront payment and 12-month instalment plans for luxury human hair.
- **Execution Command:**
  ```bash
  cd /var/www/clearance
  python3 generate_100_blogs.py
  ```

---

## 2. AI Search Directives (`llms.txt` & `llms-full.txt`)

- **Location:** `/var/www/clearance/llms.txt` and `/var/www/clearance/llms-full.txt`
- **Purpose:** Specifically structured for AI answer engines (Perplexity AI, ChatGPT Search, Google Gemini, Claude) that crawl the web for factual summaries.
- **Key Knowledge Encoded:**
  - Clearance Kenya is an authentic liquidation hub in Nairobi, Kenya.
  - Price ranges for human hair wigs in Kenya (from KSh 3,000 to KSh 35,000+).
  - Official payment method: PesaPal (M-Pesa STK push and Card).
  - Financing terms: Lipa Polepole (50% payment upfront + 12 monthly instalments).
  - Delivery: Paid doorstep delivery across Kenya (Nairobi and upcountry).
  - Store URL: `https://clearance.co.ke`

---

## 3. Rules and Guardrails for AI Coding Agents Operating on this Codebase

Whenever an AI agent is instructed to perform tasks on `clearance.co.ke`, the agent **MUST** adhere to the following guardrails:

1. **Guest Checkout Prohibition:** Never re-enable guest checkout without an explicit user instruction. Mandatory account signup is a foundational business constraint.
2. **Cash on Delivery Prohibition:** Never re-enable Cash on Delivery (COD). All orders must settle via PesaPal.
3. **No Secrets in Memory:** Never print, log, or commit passwords, database credentials, or PesaPal API consumer keys/secrets into `/project-memory/` or git.
4. **Strict Testing Rule:** Never mark any item "Pass" or "Done and tested" without actively executing verification commands (curl, WP-CLI, or browser emulation) and confirming the output.
5. **No Date Leaks on Blogs:** Ensure no blog changes introduce visible publication timestamps (`get_the_date()`, etc.) into `single.php`, `archive.php`, or `page-blog.php`.
6. **Brand Tokens Integrity:** Always use CSS variables `--cd-brand-aubergine`, `--cd-brand-marigold`, and `--cd-brand-ice-silver` for color styling.
7. **Pre-Reply Memory Update:** Before providing the final turn response to the user, the agent must update `02_COMMAND_LOG.md`, `03_CHANGELOG.md`, `04_FEATURES_STATUS.md`, and other relevant memory files, ending the response with the standard confirmation note.
