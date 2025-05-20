document.addEventListener("DOMContentLoaded", () => {
    const translations = {
        "居酒屋五叉炉 (徒歩5分)": {
            "ja": "居酒屋五叉炉 (徒歩5分)",
            "en": "Izakaya Gosaro (5 minutes on foot)",
            "zh": "居酒屋五叉炉（步行5分钟）",
            "ko": "이자카야 고사로 (도보 5분)"
        }
    };

    const defaultLang = "en"; // デフォルト言語を英語に設定
    const userLang = navigator.language.slice(0, 2); // ユーザーの言語コードを取得
    const lang = translations["居酒屋五叉炉 (徒歩5分)"][userLang]
        ? userLang
        : defaultLang;

    const h3Element = document.getElementById("izakaya-gosaro");
    const h2Element = document.getElementById("izakaya-gosaro-translation");

    if (h3Element && h2Element) {
        const originalText = h3Element.textContent.trim();
        if (translations[originalText]) {
            h2Element.textContent = translations[originalText][lang];
        } else {
            h2Element.textContent = originalText; // 翻訳がない場合はそのまま表示
        }
    }
});
// JavaScript Document