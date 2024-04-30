import os
import requests
import concurrent.futures
from colorama import Fore, Style

current_dir = os.getcwd()

def check_file(file):
    url = 'http://localhost:8000/' + os.path.relpath(file, current_dir)
    print(Fore.BLUE + 'Checking file:', file, 'URL:', url + Style.RESET_ALL)
    response = requests.get(url)
    if response.status_code != 404:
        print(Fore.GREEN + url, response.status_code + Style.RESET_ALL)
    
    url_traversal = 'http://localhost:8000/' + '/'.join(['..' for i in range(12)]) + os.path.relpath(file, current_dir)
    print(Fore.YELLOW + 'Checking file for directory traversal vulnerability:', file, 'URL:', url_traversal + Style.RESET_ALL)
    response_traversal = requests.get(url_traversal)
    if response_traversal.status_code != 404:
        print(Fore.RED + 'Directory traversal vulnerability detected:', url_traversal + Style.RESET_ALL)

with concurrent.futures.ThreadPoolExecutor() as executor:
    for root, dirs, files in os.walk(current_dir):
        for file in files:
            executor.submit(check_file, os.path.join(root, file))