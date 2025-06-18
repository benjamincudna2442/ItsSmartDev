from flask import Flask, request, jsonify
import requests
from bs4 import BeautifulSoup

app = Flask(__name__)

API_URL = "https://instsaves.pro/wp-json/visolix/api/download"
HEADERS = {
    "Content-Type": "application/json",
    "User-Agent": "Mozilla/5.0"
}

def fetch_html(insta_url):
    payload = {
        "url": insta_url,
        "format": "",
        "captcha_response": None
    }
    response = requests.post(API_URL, json=payload, headers=HEADERS)
    response.raise_for_status()
    json_data = response.json()
    if not json_data.get("status") or not json_data.get("data"):
        return None
    return json_data["data"]

def parse_media(html_content):
    soup = BeautifulSoup(html_content, "html.parser")
    media_boxes = soup.select(".visolix-media-box")

    results = []
    image_count = 1
    video_count = 1

    for box in media_boxes:
        img_tag = box.find("img", recursive=False)
        preview_img = img_tag["src"] if img_tag else None

        download_tag = box.find("a", class_="visolix-download-media", href=True)
        download_url = download_tag["href"] if download_tag else None
        download_text = download_tag.text.lower() if download_tag else ""

        if "video" in download_text:
            label = f"video{video_count}"
            video_count += 1
        elif "image" in download_text:
            label = f"image{image_count}"
            image_count += 1
        elif "story" in download_text:
            label = f"story_video{video_count}"
            video_count += 1
        else:
            label = "thumbnail"

        results.append({
            "label": label,
            "thumbnail": preview_img,
            "download": download_url
        })

    return results

@app.route("/")
def home():
    return jsonify({
        "message": "📥 Welcome to Instagram Universal Media Downloader API!",
        "supported_media": [
            "Reels",
            "Posts (Single & Album)",
            "IGTV",
            "Stories"
        ],
        "usage": "/dl?url=YOUR_INSTAGRAM_LINK",
        "API Dev": "@TheSmartDev",
        "API Owner": "@ISmartCoder"
    })

@app.route("/dl")
def download():
    url = request.args.get("url")
    if not url:
        return jsonify({
            "error": "Missing 'url' parameter.",
            "API Dev": "@TheSmartDev",
            "API Owner": "@ISmartCoder"
        }), 400

    try:
        html = fetch_html(url)
        if not html:
            return jsonify({
                "error": "Media not found or unsupported.",
                "API Dev": "@TheSmartDev",
                "API Owner": "@ISmartCoder"
            }), 404

        media_list = parse_media(html)

        return jsonify({
            "status": "success",
            "media_count": len(media_list),
            "results": media_list,
            "API Dev": "@TheSmartDev",
            "API Owner": "@ISmartCoder"
        })

    except Exception as e:
        return jsonify({
            "error": str(e),
            "API Dev": "@TheSmartDev",
            "API Owner": "@ISmartCoder"
        }), 500

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=8080)
