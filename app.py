from flask import Flask, render_template, request, redirect, url_for, session, flash
from flask_sqlalchemy import SQLAlchemy
from datetime import datetime

app = Flask(__name__)
app.config['SECRET_KEY'] = 'votre_clé_secrète'  # À changer en production
app.config['SQLALCHEMY_DATABASE_URI'] = 'sqlite:///restaurant.db'
db = SQLAlchemy(app)

# Modèles de données
class Restaurant(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    nom = db.Column(db.String(100), nullable=False)
    description = db.Column(db.Text)
    plats = db.relationship('Plat', backref='restaurant', lazy=True)

class Plat(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    nom = db.Column(db.String(100), nullable=False)
    description = db.Column(db.Text)
    prix = db.Column(db.Float, nullable=False)
    restaurant_id = db.Column(db.Integer, db.ForeignKey('restaurant.id'), nullable=False)

class Panier(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    plat_id = db.Column(db.Integer, db.ForeignKey('plat.id'), nullable=False)
    quantite = db.Column(db.Integer, nullable=False)
    session_id = db.Column(db.String(100), nullable=False)
    date_ajout = db.Column(db.DateTime, default=datetime.utcnow)

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
    
    panier_item = Panier(
        plat_id=plat_id,
        quantite=1,
        session_id=session['session_id']
    )
    db.session.add(panier_item)
    db.session.commit()
    flash('Plat ajouté au panier avec succès!')
    return redirect(request.referrer)

@app.route('/panier')
def voir_panier():
    if 'session_id' not in session:
        return render_template('panier.html', items=[])
    
    items_panier = Panier.query.filter_by(session_id=session['session_id']).all()
    return render_template('panier.html', items=items_panier)

@app.route('/contact')
def contact():
    return render_template('contact.html')

with app.app_context():
    db.create_all()
    
    # Ajouter les restaurants si la base de données est vide
    if not Restaurant.query.first():
        restaurants = [
            {
                'nom': 'Alkimia',
                'description': 'Restaurant gastronomique aux saveurs uniques'
            },
            {
                'nom': 'Lagon',
                'description': 'Spécialités de fruits de mer et poissons frais'
            },
            {
                'nom': 'Plancha',
                'description': 'Grillades et spécialités espagnoles'
            },
            {
                'nom': 'Tandem',
                'description': 'Cuisine fusion moderne et créative'
            }
        ]
        
        for restaurant_data in restaurants:
            restaurant = Restaurant(**restaurant_data)
            db.session.add(restaurant)
            
            # Ajouter quelques plats pour chaque restaurant
            for i in range(3):
                plat = Plat(
                    nom=f"Plat {i+1}",
                    description=f"Description du plat {i+1}",
                    prix=15.99 + i,
                    restaurant=restaurant
                )
                db.session.add(plat)
        
        db.session.commit()

if __name__ == '__main__':
    app.run(debug=True, port=5001)
