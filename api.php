<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = file_get_contents("php://input");
    $data = json_decode($input, true);

    if (isset($data['name']) && isset($data['email']) && isset($data['date']) && isset($data['time'])) {
        $name = htmlspecialchars($data['name']);
        $email = htmlspecialchars($data['email']);
        $date = htmlspecialchars($data['date']);
        $time = htmlspecialchars($data['time']);

        $appointment = array(
            "name" => $name,
            "email" => $email,
            "date" => $date,
            "time" => $time
        );

        $filename = 'appointments.json';

        // Laden vorhandener Termine
        $appointments = array();
        if (file_exists($filename)) {
            $appointments = json_decode(file_get_contents($filename), true);
        }

        // Überprüfen, ob der Termin bereits belegt ist
        foreach ($appointments as $existingAppointment) {
            if ($existingAppointment['date'] == $date && $existingAppointment['time'] == $time) {
                echo json_encode(array("status" => "error", "message" => "Dieser Termin ist bereits belegt. Bitte wählen Sie einen anderen Termin."));
                exit;
            }
        }

        // Termin hinzufügen und speichern
        $appointments[] = $appointment;
        file_put_contents($filename, json_encode($appointments));

        // Bestätigungs-E-Mail senden (optional)
        $to = $email;
        $subject = "Terminbestätigung";
        $body = "Hallo $name,\n\nIhr Termin am $date um $time wurde erfolgreich gebucht.\n\nMit freundlichen Grüßen,\nFriseurladen";
        $headers = "From: no-reply@friseurladen.com";

        if (mail($to, $subject, $body, $headers)) {
            echo json_encode(array("status" => "success", "message" => "Vielen Dank, $name. Ihr Termin wurde erfolgreich gebucht und eine Bestätigung wurde an $email gesendet."));
        } else {
            echo json_encode(array("status" => "success", "message" => "Ihr Termin wurde gebucht, aber die Bestätigungs-E-Mail konnte nicht gesendet werden."));
        }
    } else {
        echo json_encode(array("status" => "error", "message" => "Ungültige Anforderung."));
    }
} else {
    echo json_encode(array("status" => "error", "message" => "Ungültige Anforderung."));
}
?>
