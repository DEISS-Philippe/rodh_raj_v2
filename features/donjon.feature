Feature: Room
  vérifie que le passage d'une salle à l'autre s'effectue correctement.

  Scenario: The user plays and go to the next room
    Given I am on "/donjon-vanilla/1"
    Then  I should see "Entrer dans le donjon"
    When I s
    Given A user roomNumber equal to 1
    And the currentRoomCode is equal to "entree_du_donjon_1"
    When the user click the link to go to the next Room
    Then the next Room has startRoomAction
    And the user roomNumber is equal to 2

#  Scenario: The user plays and go to the next room
#    Given A user roomNumber equal to 1
#    And the currentRoomCode is equal to "entree_du_donjon_1"
#    When the user click the link to go to the next Room
#    Then the next Room has startRoomAction
#    And the user roomNumber is equal to 2