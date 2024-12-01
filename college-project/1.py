import requests
from bs4 import BeautifulSoup

url = 'https://www.yellowpages.com.au/find/dentist/cairns-qld-4870'
response = requests.get(url)

soup = BeautifulSoup(response.text, 'html.parser')
divs = soup.find_all('div', class_='cINsGc')

for div in divs:
    print(div.text)
