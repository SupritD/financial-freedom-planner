import zipfile
import xml.etree.ElementTree as ET
import glob
import os

def extract_text_from_docx(docx_path):
    try:
        with zipfile.ZipFile(docx_path) as z:
            xml_content = z.read('word/document.xml')
            tree = ET.fromstring(xml_content)
            
            # The namespace for Word XML
            ns = {'w': 'http://schemas.openxmlformats.org/wordprocessingml/2006/main'}
            
            # Find all text elements
            paragraphs = []
            for p in tree.findall('.//w:p', ns):
                texts = [node.text for node in p.findall('.//w:t', ns) if node.text]
                if texts:
                    paragraphs.append(''.join(texts))
            
            return '\n'.join(paragraphs)
    except Exception as e:
        return f"Error reading {docx_path}: {e}"

def main():
    docx_files = glob.glob('docx/*.docx')
    output = []
    for file in docx_files:
        output.append(f"--- {os.path.basename(file)} ---")
        output.append(extract_text_from_docx(file))
        output.append("\n")
        
    with open('docx_contents.txt', 'w', encoding='utf-8') as f:
        f.write('\n'.join(output))
    print("Done. Contents written to docx_contents.txt")

if __name__ == '__main__':
    main()
