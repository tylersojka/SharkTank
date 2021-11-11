from flask import Flask, jsonify, request
from flask_sqlalchemy import SQLAlchemy

app = Flask(__name__)
app.config["SQLALCHEMY_DATABASE_URI"] = "sqlite:///database/shark_tank.db"
app.config["SQLALCHEMY_TRACK_MODIFICATIONS"] = False
db = SQLAlchemy(app)

class Teams(db.Model):
    __tablename__ = "teams"
    team_id = db.Column(db.Integer, primary_key = True)
    team_name = db.Column(db.String)

class Users(db.Model):
    __tablename__ = "users"
    user_id = db.Column(db.String, primary_key = True)
    user_name = db.Column(db.String)
    team_id = db.Column(db.Integer, db.ForeignKey("teams.team_id"))
    coins = db.Column(db.Integer, default=100)
    password = db.Column(db.String)

class AssignedCoins(db.Model):
    __tablename__ = "assigned_coins"
    user_id = db.Column(db.String, db.ForeignKey("users.users_id"), primary_key = True)
    team_id = db.Column(db.Integer, db.ForeignKey("teams.team_id"), primary_key = True)
    coin_amount = db.Column(db.Integer, default=0)

@app.route("/")
def index():
    return jsonify("Placeholder to avoid an error mesage here")

@app.route("/api/user/coins", methods=["GET"])
def get_user_coins():
    user_id = request.args.get("userId")
    user_coins = db.session.query(Users).filter(Users.user_id == user_id).first()
    coins_dict = {
        "userId": user_coins.user_id,
        "coins": user_coins.coins,
    }
    return jsonify(coins_dict)

@app.route("/api/team", methods=["GET"])
def get_teams():
    teams_raw = db.session.query(Teams)
    teams_parsed = []
    for team in teams_raw:
        team_dict = {
            "teamId": team.team_id,
            "teamName": team.team_name
        }
        teams_parsed.append(team_dict)
    return jsonify(teams_parsed)

@app.route("/api/team/coins", methods=["GET", "POST"])
def get_team_coins():
    team_id = request.args.get("teamId")

    if request.method == "GET":
        team_coins = db.session.query(AssignedCoins).filter(AssignedCoins.team_id == team_id).all()
        team_coins_parsed = []
        for transaction in team_coins:
            coin_dict = {
                "userId": transaction.user_id,
                "teamId": transaction.team_id,
                "coins": transaction.coin_amount,
            }
            team_coins_parsed.append(coin_dict)
        return jsonify(team_coins_parsed)

    if request.method == "POST":
        request_data = request.get_json()
        user_coins_total = db.session.query(Users).filter(Users.user_id == request_data["user_id"]).first()
        coins_initial = user_coins_total.coins
        new_coin_total = coins_initial - request_data["coins"]
        new_user_coins_total = {
            "user_id": user_coins_total.user_id,
            "user_name": user_coins_total.user_name,
            "team_id": user_coins_total.team_id,
            "coins": new_coin_total,
            "password": user_coins_total.password,
        }
        db.session.add(AssignedCoins(**request_data))
        db.session.add(Users(**new_user_coins_total))
        db.session.commit()
        return jsonify("new_user_coins_total")

if __name__ == "__main__":
    app.run(debug=True)