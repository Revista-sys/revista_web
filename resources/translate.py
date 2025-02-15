from deepl import Translator
import json

def translate_package():
    # Initialize DeepL translator with your API key
    DEEPL_API_KEY = "your-api-key-here"  # Replace with your DeepL API key
    translator = Translator(DEEPL_API_KEY)
    
    # Read the source file
    try:
        with open('source.json', 'r', encoding='utf-8') as file:
            data = json.load(file)
    except FileNotFoundError:
        print("Source file not found!")
        return
    except json.JSONDecodeError:
        print("Invalid JSON format!")
        return

    # Translate values to Arabic
    translated_data = {}
    for key, value in data.items():
        try:
            # Skip empty values or numeric values
            if not value or value.isdigit():
                translated_data[key] = value
                continue
                
            # Translate only the value, keep the key as is
            translation = translator.translate_text(value, target_lang="AR")
            translated_data[key] = translation.text
        except Exception as e:
            print(f"Error translating {key}: {str(e)}")
            translated_data[key] = value  # Keep original if translation fails

    # Save translated file
    try:
        with open('translated_arabic.json', 'w', encoding='utf-8') as file:
            json.dump(translated_data, file, ensure_ascii=False, indent=2)
        print("Translation completed successfully!")
    except Exception as e:
        print(f"Error saving file: {str(e)}")

if __name__ == "__main__":
    translate_package()
