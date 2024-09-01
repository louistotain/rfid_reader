import requests
import time

def lire_badge():
    id_badge = input("ID du badge : ")
    return id_badge

def envoyer_badge(api_url, id_badge):
    try:
        response = requests.post(api_url, json={"id_badge": id_badge})
        if response.status_code == 200:
            print("ID de badge envoyé avec succès !")
        else:
            print(f"Erreur lors de l'envoi de l'ID de badge. Code de statut : {response.status_code}")
    except requests.exceptions.RequestException as e:
        print(f"Erreur de connexion à l'API : {e}")

if __name__ == "__main__":
    api_url = 'http://localhost/rfid_reader/add_badge.php'
    while True:
        id_badge = lire_badge()
        if id_badge:
            envoyer_badge(api_url, id_badge)
