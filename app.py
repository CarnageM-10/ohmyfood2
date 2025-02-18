from flask import Flask, render_template, request, redirect, url_for, session, flash
from flask_sqlalchemy import SQLAlchemy
from datetime import datetime

app = Flask(__name__)
app.config['SECRET_KEY'] = 'votre_clé_secrète'  # À changer en production
app.config['SQLALCHEMY_DATABASE_URI'] = 'sqlite:///restaurant.db'
app.config['SQLALCHEMY_TRACK_MODIFICATIONS'] = False
db = SQLAlchemy(app)

# Modèles de données
class Restaurant(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    nom = db.Column(db.String(100), nullable=False)
    description = db.Column(db.Text)
    image = db.Column(db.String(255))  # Colonne pour l'image
    plats = db.relationship('Plat', backref='restaurant', lazy=True)

class Plat(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    nom = db.Column(db.String(100), nullable=False)
    description = db.Column(db.Text)
    prix = db.Column(db.Float, nullable=False)
    restaurant_id = db.Column(db.Integer, db.ForeignKey('restaurant.id'), nullable=False)
    image = db.Column(db.String(255))  # Colonne pour l'image

class Panier(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    plat_id = db.Column(db.Integer, db.ForeignKey('plat.id'), nullable=False)
    quantite = db.Column(db.Integer, nullable=False)
    session_id = db.Column(db.String(100), nullable=False)
    date_ajout = db.Column(db.DateTime, default=datetime.utcnow)

class Contact(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    nom = db.Column(db.String(100), nullable=False)
    email = db.Column(db.String(100), nullable=False)
    message = db.Column(db.Text, nullable=False)
    date_envoi = db.Column(db.DateTime, default=datetime.utcnow)

class CarteBancaire(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    nomBanque = db.Column(db.String(100), nullable=False)
    nom = db.Column(db.String(100), nullable=False)
    prenom = db.Column(db.String(100), nullable=False)
    cvv = db.Column(db.String(4), nullable=False)
    dateExp = db.Column(db.String(5), nullable=False)  # Format MM/AA
    numCard = db.Column(db.String(16), nullable=False)
    montantCard = db.Column(db.Float, nullable=False)

# Routes
@app.route('/')
def index():
    restaurants = Restaurant.query.all()
    return render_template('index.html', restaurants=restaurants)

@app.route('/restaurant/<int:restaurant_id>')
def restaurant_detail(restaurant_id):
    restaurant = Restaurant.query.get_or_404(restaurant_id)
    return render_template('restaurant.html', restaurant=restaurant)

@app.route('/ajouter_panier', methods=['POST'])
def ajouter_panier():
    plat_id = request.form.get('plat_id')
    
    if 'session_id' not in session:
        session['session_id'] = str(datetime.now().timestamp())
    
    existing_item = Panier.query.filter_by(plat_id=plat_id, session_id=session['session_id']).first()
    
    if existing_item:
        existing_item.quantite += 1
        flash('Quantité mise à jour dans le panier!')
    else:
        panier_item = Panier(
            plat_id=plat_id,
            quantite=1,
            session_id=session['session_id']
        )
        db.session.add(panier_item)
        flash('Plat ajouté au panier avec succès!')

    plat = Plat.query.get(plat_id)
    total = session.get('total', 0) + plat.prix  # Ajoutez le prix du plat au total
    session['total'] = total

    db.session.commit()
    return redirect(request.referrer)

@app.route('/supprimer_commande/<int:plat_id>', methods=['POST'])
def supprimer_commande(plat_id):
    paniers = Panier.query.filter_by(plat_id=plat_id).order_by(Panier.date_ajout.desc()).all()
    
    if paniers:
        db.session.delete(paniers[0])  
        db.session.commit()
        flash('Le plat a été supprimé avec succès.', 'success')
    else:
        flash('Aucun plat trouvé à supprimer.', 'error')

    return redirect(url_for('voir_panier'))  

@app.route('/passer_commande', methods=['GET', 'POST'])
def passer_commande():
    if request.method == 'POST':
        nom = request.form['nom']
        prenom = request.form['prenom']
        cvv = request.form['cvv']
        dateExp = request.form['dateExp']
        numCard = request.form['numCard']

        # Récupérer le total du panier depuis la session
        total_panier = session.get('total', 0)  # Assurez-vous que le total est stocké dans la session
        print(f'Total du panier: {total_panier}')  # Vérifiez la valeur du total

        # Récupérer la carte bancaire
        carte = CarteBancaire.query.filter_by(nom=nom, prenom=prenom, cvv=cvv, dateExp=dateExp, numCard=numCard).first()

        if not carte:
            flash('Informations de carte incorrectes.', 'error')
            return redirect(url_for('voir_panier'))

        # Vérifier le solde de la carte
        montantCard = carte.montantCard
        print(f'Solde de la carte avant soustraction: {montantCard}')  # Vérifiez la valeur du solde

        # Convertir le total en float si ce n'est pas déjà fait
        total_panier_float = float(total_panier)

        if montantCard < total_panier_float:
            flash('Solde insuffisant.', 'error')
            return redirect(url_for('voir_panier'))

        # Effectuer la soustraction
        carte.montantCard -= total_panier_float 
        print(f'Solde de la carte après soustraction: {carte.montantCard}')  # Vérifiez la valeur après soustraction

        # Mettre à jour la base de données
        db.session.commit() 

        # Supprimer les commandes dans le panier
        session_id = request.cookies.get('session_id') 
        db.session.query(Panier).filter_by(session_id=session_id).delete()
        db.session.commit()

        flash(f'Panier validé ! Votre solde actuel est de : {carte.montantCard}€', 'success')
        return redirect(url_for('index')) 

    return render_template('paiement.html')

@app.route('/panier')
def voir_panier():
    if 'session_id' not in session:
        return render_template('panier.html', items=[], total=0)
    
    items_panier = Panier.query.filter_by(session_id=session['session_id']).all()
    total = 0
    plats_regroupes = {}

    for item in items_panier:
        plat = Plat.query.get(item.plat_id)
        if plat.id in plats_regroupes:
            plats_regroupes[plat.id]['quantite'] += item.quantite
        else:
            plats_regroupes[plat.id] = {
                'plat': plat,
                'quantite': item.quantite
            }
        total += plat.prix * item.quantite  # Calculez le total

    # Stocker le total dans la session pour l'utiliser lors du passage de commande
    session['total'] = total

    return render_template('panier.html', plats=plats_regroupes.values(), total=total)

@app.route('/contact', methods=['GET', 'POST'])
def contact():
    if request.method == 'POST':
        nom = request.form['nom']
        email = request.form['email']
        message = request.form['message']
        
        # Enregistrer le message dans la base de données
        contact_message = Contact(nom=nom, email=email, message=message)
        db.session.add(contact_message)
        db.session.commit()
        
        flash(f'Votre message a bien été transmis, {nom}. Nous vous répondrons dans les plus brefs délais.', 'success')
        return redirect(url_for('contact'))
    
    return render_template('contact.html')

@app.route('/carte', methods=['GET', 'POST'])
def carte():
    if request.method == 'POST':
        nomBanque = request.form['nomBanque']
        nom = request.form['nom']
        prenom = request.form['prenom']
        cvv = request.form['cvv']
        dateExp = request.form['dateExp']
        numCard = request.form['numCard']
        montantCard = request.form['montantCard']
        
        # Enregistrer les informations de la carte dans la base de données
        carte_bancaire = CarteBancaire(
            nomBanque=nomBanque,
            nom=nom,
            prenom=prenom,
            cvv=cvv,
            dateExp=dateExp,
            numCard=numCard,
            montantCard=montantCard
        )
        db.session.add(carte_bancaire)
        db.session.commit()
        
        flash('Les informations de votre carte ont été enregistrées avec succès.', 'success')
        return redirect(url_for('carte'))
    
    return render_template('carte.html')

@app.route('/carte_detail')
def carte_detail():
    carte_info = CarteBancaire.query.first() 
    return render_template('detailCard.html', carte=carte_info)

# Création de la base de données et ajout de données initiales
with app.app_context():
    db.create_all()
    
    if not Restaurant.query.first():
        restaurants = [
            {
                'nom': 'Alkimia',
                'description': 'Restaurant gastronomique aux saveurs uniques',
                'image': 'images/restaurants/alkimia.jpg'
            },
            {
                'nom': 'Lagon',
                'description': 'Spécialités de fruits de mer et poissons frais',
                'image': 'images/restaurants/lagon.jpg'
            },
            {
                'nom': 'Plancha',
                'description': 'Grillades et spécialités espagnoles',
                'image': 'images/restaurants/plancha.jpg'
            },
            {
                'nom': 'Tandem',
                'description': 'Cuisine fusion moderne et créative',
                'image': 'images/restaurants/tandem.jpg'
            }
        ]
        
        for restaurant_data in restaurants:
            restaurant = Restaurant(**restaurant_data)
            db.session.add(restaurant)
            db.session.commit()  # Commit après chaque restaurant pour éviter les problèmes de clé primaire
            
            for i in range(3):
                                plat = Plat(
                    nom=f"Plat {i+1}",
                    description=f"Description du plat {i+1}",
                    prix=15.99 + i,
                    restaurant=restaurant,
                    image=f'images/plats/{restaurant_data["nom"].lower()}/plat_{i+1}.jpeg'
                )
        db.session.add(plat)
        
        db.session.commit()

if __name__ == '__main__':
    app.run(debug=True, port=5001)