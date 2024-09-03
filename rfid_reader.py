import requests
import time

def lire_badge():
    rfid = input("RFID : ")
    return rfid

def envoyer_badge(api_url, rfid):
    try:
        response = requests.post(api_url, json={"rfid": rfid})
        if response.status_code == 200:
            print("ID de badge envoyé avec succès !")
        else:
            print(f"Erreur lors de l'envoi de l'ID de badge. Code de statut : {response.status_code}")
    except requests.exceptions.RequestException as e:
        print(f"Erreur de connexion à l'API : {e}")

if __name__ == "__main__":
    api_url = 'http://timeclock/add_badge.php'
    while True:
        rfid = lire_badge()
        if rfid:
            envoyer_badge(api_url, rfid)
