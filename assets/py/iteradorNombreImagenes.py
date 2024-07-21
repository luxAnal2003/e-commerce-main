import os
import tkinter as tk
from tkinter import filedialog, messagebox
from PIL import Image
from datetime import datetime

def rename_images():
    folder_path = filedialog.askdirectory()
    if not folder_path:
        return
    
    images = [f for f in os.listdir(folder_path) if f.lower().endswith(('jpg', 'jpeg', 'png', 'webp'))]
    if not images:
        messagebox.showinfo("No Images", "No images found in the selected folder.")
        return
    
    images_with_dates = []
    
    for image in images:
        image_path = os.path.join(folder_path, image)
        creation_time = os.path.getctime(image_path)
        images_with_dates.append((image, creation_time))
    
    images_with_dates.sort(key=lambda x: x[1])
    
    for i, (image, _) in enumerate(images_with_dates):
        new_name = f"imagen_{i+1}.jpg"
        old_path = os.path.join(folder_path, image)
        new_path = os.path.join(folder_path, new_name)
        
        # Convert image to jpg if necessary
        if not image.lower().endswith('jpg'):
            with Image.open(old_path) as img:
                rgb_img = img.convert('RGB')
                rgb_img.save(new_path)
            os.remove(old_path)
        else:
            os.rename(old_path, new_path)
    
    messagebox.showinfo("Success", "Images have been renamed successfully.")

root = tk.Tk()
root.title("Image Renamer")
root.geometry("300x150")

frame = tk.Frame(root)
frame.pack(pady=20)

label = tk.Label(frame, text="Select a folder to rename images:")
label.pack(pady=5)

btn = tk.Button(frame, text="Select Folder", command=rename_images)
btn.pack(pady=5)

root.mainloop()
