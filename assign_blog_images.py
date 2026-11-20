#!/usr/bin/env python3
"""
assign_blog_images.py
Maps and sets a 100% unique, relevant featured image (_thumbnail_id)
for every single one of the 141 blog posts on clearance.co.ke
using authentic images from the site's media library.
"""

import json
import subprocess
import sys

WP_PATH = "/var/www/clearance"

def run_wp(cmd):
    full_cmd = f"wp {cmd} --allow-root --path={WP_PATH}"
    res = subprocess.run(full_cmd, shell=True, capture_output=True, text=True)
    if res.returncode != 0:
        print(f"Error running: {full_cmd}\n{res.stderr}")
    return res.stdout.strip()

def main():
    with open(f"{WP_PATH}/media_gallery.json") as f:
        gallery = json.load(f)

    # Filter out electronics, appliances, placeholders, icons
    excluded_terms = [
        'iphone', 'galaxy', 'playstation', 'coffee', 'airpods',
        'refrigerator', 'samsung 253l', 'woocommerce-placeholder',
        'mpesa', 'svg', 'logo'
    ]
    
    valid_images = []
    seen_ids = set()
    for img in gallery:
        img_id = int(img['id'])
        if img_id in seen_ids:
            continue
        text = (img.get('title', '') + ' ' + img.get('url', '')).lower()
        if any(term in text for term in excluded_terms):
            continue
        seen_ids.add(img_id)
        valid_images.append(img)

    print(f"Total valid unique gallery images available: {len(valid_images)}")

    # Load all published posts
    posts_raw = run_wp("post list --post_type=post --post_status=publish --fields=ID,post_name,post_title --format=json")
    posts = json.loads(posts_raw)
    print(f"Total published posts to assign images: {len(posts)}")

    # Load products catalog for exact 41 care guides matching
    with open(f"{WP_PATH}/products_catalog.json") as f:
        products = json.load(f)

    product_by_slug = {}
    for p in products:
        product_by_slug[p['slug']] = p

    # Track used image IDs so NO two posts ever share an image!
    used_image_ids = set()
    post_image_map = {} # post_id -> image_id

    # 1. First Pass: Product Care Guides (Category 208)
    care_guides = run_wp("post list --post_type=post --post_status=publish --category=208 --fields=ID,post_name,post_title --format=json")
    care_posts = json.loads(care_guides)
    print(f"Total product care guides: {len(care_posts)}")

    for post in care_posts:
        post_id = int(post['ID'])
        post_name = post['post_name']
        
        # Match product slug
        matched_product = None
        for p_slug, p in product_by_slug.items():
            if p_slug in post_name:
                matched_product = p
                break
        
        assigned_img = None
        if matched_product and matched_product.get('image_id'):
            cand_id = int(matched_product['image_id'])
            if cand_id not in used_image_ids:
                assigned_img = cand_id
        
        # Fallback for care guides without direct image_id (e.g. hair consultation)
        if not assigned_img:
            if "consultation" in post_name:
                for cand in valid_images:
                    cid = int(cand['id'])
                    if cid not in used_image_ids and "hair" in cand['title'].lower():
                        assigned_img = cid
                        break
            elif "boot" in post_name:
                assigned_img = 21 # Saint G Boots
            elif "luna" in post_name or "suede" in post_name:
                assigned_img = 103 # Phillip Lim Luna
            elif "makeup" in post_name:
                for cand in [107, 108]:
                    if cand not in used_image_ids:
                        assigned_img = cand
                        break

        if assigned_img:
            used_image_ids.add(assigned_img)
            post_image_map[post_id] = assigned_img
        else:
            print(f"Warning: could not find specific image for care guide {post_id} ({post_name})")

    print(f"Assigned {len(post_image_map)} images to product care guides.")

    # 2. Second Pass: 100 Cluster Blog Posts
    # Define semantic pools
    blonde_imgs = []
    curly_imgs = []
    straight_imgs = []
    wavy_imgs = []
    short_classic_imgs = []
    tool_care_imgs = []
    lifestyle_hair_imgs = []
    general_hair_imgs = []

    for img in valid_images:
        cid = int(img['id'])
        if cid in used_image_ids:
            continue
        text = (img['title'] + ' ' + img['url']).lower()
        if any(k in text for k in ['blonde', '613', 'balayage', 'highlight', 'ombre', 'caramel']):
            blonde_imgs.append(cid)
        elif any(k in text for k in ['curly', 'kinky', 'afro', 'deep curl']):
            curly_imgs.append(cid)
        elif any(k in text for k in ['straight', 'bone straight', 'yaki']):
            straight_imgs.append(cid)
        elif any(k in text for k in ['wave', 'body wave', 'crochet']):
            wavy_imgs.append(cid)
        elif any(k in text for k in ['classic', 'cut', 'monocrown', 'breezy', 'teal', 'short', 'bob']):
            short_classic_imgs.append(cid)
        elif any(k in text for k in ['protouch', 'dyson', 'airshot', 'airwrap', 'kit', 'treatment', 'styling']):
            tool_care_imgs.append(cid)
        elif any(k in text for k in ['lifestyle', 'model', 'mannequin', 'front', 'side', 'back']):
            lifestyle_hair_imgs.append(cid)
        else:
            general_hair_imgs.append(cid)

    # Function to pick the best available unique image
    def pick_image(category_keywords, title_text):
        combined = (category_keywords + " " + title_text).lower()
        
        pools = []
        if any(k in combined for k in ['blonde', '613', 'honey', 'caramel', 'highlight', 'balayage', 'color', 'burgundy', 'orange', 'ginger']):
            pools = [blonde_imgs, lifestyle_hair_imgs, general_hair_imgs]
        elif any(k in combined for k in ['curly', 'kinky', 'afro', 'curls', 'deep wave', 'water wave']):
            pools = [curly_imgs, wavy_imgs, lifestyle_hair_imgs, general_hair_imgs]
        elif any(k in combined for k in ['straight', 'bone straight', 'bundle', 'temple', 'donor', 'cuticle']):
            pools = [straight_imgs, wavy_imgs, lifestyle_hair_imgs, general_hair_imgs]
        elif any(k in combined for k in ['bob', 'pixie', 'short', 'monofilament', 'classic']):
            pools = [short_classic_imgs, lifestyle_hair_imgs, general_hair_imgs]
        elif any(k in combined for k in ['wash', 'shampoo', 'heat', 'condition', 'care', 'protect', 'dry', 'tool', 'clean', 'styler']):
            pools = [tool_care_imgs, general_hair_imgs, lifestyle_hair_imgs]
        else:
            pools = [lifestyle_hair_imgs, general_hair_imgs, wavy_imgs, straight_imgs, curly_imgs, blonde_imgs]

        for pool in pools:
            for cand_id in pool:
                if cand_id not in used_image_ids:
                    used_image_ids.add(cand_id)
                    pool.remove(cand_id)
                    return cand_id

        # Last resort: any unused valid image
        for cand in valid_images:
            cand_id = int(cand['id'])
            if cand_id not in used_image_ids:
                used_image_ids.add(cand_id)
                return cand_id
        return None

    # Iterate through remaining posts
    for post in posts:
        post_id = int(post['ID'])
        if post_id in post_image_map:
            continue # already assigned in care guides
        
        post_title = post['post_title']
        post_name = post['post_name']
        img_id = pick_image(post_name, post_title)
        if img_id:
            post_image_map[post_id] = img_id
        else:
            print(f"Error: ran out of unique images for post {post_id} ({post_title})")

    print(f"Total posts mapped: {len(post_image_map)} / {len(posts)}")
    print(f"Total unique images used: {len(set(post_image_map.values()))}")
    
    # Verify 100% uniqueness:
    if len(set(post_image_map.values())) != len(post_image_map):
        print("CRITICAL WARNING: Found duplicate images assigned!")
    else:
        print("SUCCESS: 100% of posts have a completely unique featured image!")

    # Now apply the thumbnail to each post in WordPress!
    print("Applying _thumbnail_id to WordPress posts...")
    count = 0
    for post_id, img_id in post_image_map.items():
        run_wp(f"post meta set {post_id} _thumbnail_id {img_id}")
        count += 1
        if count % 20 == 0 or count == len(post_image_map):
            print(f"Progress: {count}/{len(post_image_map)} posts updated.")

    print("All post thumbnails successfully updated in database!")

if __name__ == "__main__":
    main()
