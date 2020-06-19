import json
import requests
from user import spotify_user_id
class CreatePlaylist:
    def __init__ (self):
        self.user_id = spotify_user_id
    #Logging into YT
    def get_yt_client(self):
        pass
    #Get liked videos
    def get_liked_video(self):
        pass
    #Create A playlist
    def create_playlist(self):
        request_body = json.dumps( {
            "name" : "YT liked videos",
            "description" : "All Liked videos on YT",
            "public" : "True"
        })
        query = "https://api.spotify.com/v1/users/{}/playlists" 
        response = requests.post (
            query,
            data={
                "Content-Type" : "application/json",
                "Authorization" : "bearer {}".format(spotify_token)
            }
        )
        return response_json("id")
    #Search for song
    def get_spotify_url(self, track, id):
        query = "https://api.spotify.com/v1/search"
    #Add song to the playlist
    def add_song_to_playlist(self):
        pass
    