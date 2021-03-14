DROP TABLE IF EXISTS app_history;
CREATE VIEW app_history AS
SELECT
    `applications_v0`.`char_name` AS `app_char_name`,
    `applications_v0`.`detected_steam_name` AS `app_steam_name`,
    `applications_v0`.`discord_name` AS `app_discord_name`,
    `applications_v0`.`app_timestamp` AS `app_timestamp`,
    `applications_v0`.`phone_number` AS `phone_number`,
    `applications_v0`.`app_id` AS `app_id`,
    `applications_v0`.`status` AS `status`,
    `players`.`char_name` AS `signed_by`,
    `callsigns`.`label` AS `callsign`,
    `applications_v0`.`steam_id` AS `steam_id`,
    `applications_v0`.`signed_timestamp` AS `signed_timestamp`
FROM
    (
        (
            `applications_v0`
        LEFT JOIN `callsigns` ON
            (
                `applications_v0`.`signed_by` = `callsigns`.`assigned_steam_id`
            )
        )
    JOIN `players`
    )
WHERE
    `applications_v0`.`signed_by` = `players`.`steam_id` AND `applications_v0`.`signed_by` IS NOT NULL
ORDER BY
    `applications_v0`.`app_timestamp`
DESC;


DROP TABLE IF EXISTS old_new_players;
CREATE VIEW old_new_players AS
SELECT
    `players`.`char_name` AS `char_name`,
    `players`.`discord_name` AS `new_discord_name`,
    `old_players`.`discord_name` AS `old_discord_name`,
    `old_players`.`old_name` AS `old_name`
FROM
    (
        `old_players`
    LEFT JOIN `players` ON
        (
            `old_players`.`steam_id` = `players`.`steam_id`
        )
    );
DROP TABLE IF EXISTS public_players;
CREATE VIEW public_players AS
SELECT
    `callsigns`.`label` AS `callsign`,
    `callsigns`.`id` AS `callsign_id`,
    `players`.`char_name` AS `char_name`,
    `players`.`steam_id` AS `steam_id`,
    `players`.`steam_name` AS `steam_name`,
    `players`.`discord_name` AS `discord_name`,
    `players`.`phone_number` AS `phone_number`,
    `players`.`rank` AS `rank`,
    `ranks`.`display_name` AS `rank_label`,
    `players`.`status` AS `status`,
    `players`.`timezone` AS `timezone`,
    `players`.`last_seen` AS `last_seen`,
    `players`.`backstory` AS `backstory`,
    `players`.`av_full` AS `av_full`,
    `players`.`steam_link` AS `steam_link`,
    `players`.`employment_start` AS `employment_start`,
    `players`.`whitelisted` AS `whitelisted`
FROM
    (
        (
            `players`
        LEFT JOIN `callsigns` ON
            (
                `callsigns`.`assigned_steam_id` = `players`.`steam_id`
            )
        )
    LEFT JOIN `ranks` ON
        (
            `ranks`.`placement` = `players`.`rank`
        )
    )
WHERE
    `players`.`status` IS NOT NULL;
DROP TABLE IF EXISTS notes;
CREATE VIEW notes AS
SELECT
    `private_notes`.`id` AS `id`,
    `private_notes`.`doc_id` AS `doc_id`,
    `private_notes`.`doc_type` AS `doc_type`,
    `private_notes`.`timestamp` AS `timestamp`,
    `private_notes`.`message` AS `message`,
    `public_players`.`callsign` AS `callsign`,
    `public_players`.`char_name` AS `char_name`
FROM
    (
        `private_notes`
    JOIN `public_players` ON
        (
            `private_notes`.`steam_id` = `public_players`.`steam_id`
        )
    );
DROP TABLE IF EXISTS public_strikes;
CREATE VIEW public_strikes AS
SELECT
    `strikes`.`id` AS `id`,
    `strikes`.`steam_id` AS `steam_id`,
    `callsigns`.`label` AS `callsign`,
    `players`.`char_name` AS `char_name`,
    `strikes`.`severity` AS `severity`,
    `strikes`.`strike_title` AS `strike_title`,
    `strikes`.`strike_desc` AS `strike_desc`,
    `strikes`.`strike_evidence` AS `strike_evidence`,
    `callsignsB`.`label` AS `signed_callsign`,
    `playersB`.`char_name` AS `signed_by`,
    `strikes`.`issue_date` AS `issue_date`,
    `strikes`.`end_date` AS `end_date`
FROM
    (
        (
            (
                (
                    `strikes`
                LEFT JOIN `callsigns` ON
                    (
                        `strikes`.`steam_id` = `callsigns`.`assigned_steam_id`
                    )
                )
            LEFT JOIN `players` ON
                (
                    `strikes`.`steam_id` = `players`.`steam_id`
                )
            )
        LEFT JOIN `callsigns` `callsignsB`
        ON
            (
                `strikes`.`signed_by` = `callsignsB`.`assigned_steam_id`
            )
        )
    LEFT JOIN `players` `playersB`
    ON
        (
            `strikes`.`signed_by` = `playersB`.`steam_id`
        )
    );
DROP TABLE IF EXISTS public_verified_shifts;
CREATE VIEW public_verified_shifts AS
SELECT
    `verified_shifts`.`id` AS `id`,
    `verified_shifts`.`server` AS `server`,
    `verified_shifts`.`steam_id` AS `steam_id`,
    `verified_shifts`.`duration` AS `duration`,
    `verified_shifts`.`timestamp` AS `timestamp`,
    `verified_shifts`.`signed_by` AS `signed_by`,
    `sr1`.`timestamp` AS `time_in`,
    `sr2`.`timestamp` AS `time_out`,
    `callsigns`.`label` AS `callsign`,
    `players`.`char_name` AS `char_name`,
    `players`.`discord_name` AS `discord_name`,
    `players`.`rank` AS `rank`
FROM
    (
        (
            (
                (
                    `verified_shifts`
                LEFT JOIN `shift_records` `sr1`
                ON
                    (
                        `verified_shifts`.`inRow` = `sr1`.`id`
                    )
                )
            LEFT JOIN `shift_records` `sr2`
            ON
                (
                    `verified_shifts`.`outRow` = `sr2`.`id`
                )
            )
        LEFT JOIN `callsigns` ON
            (
                `callsigns`.`assigned_steam_id` = `verified_shifts`.`steam_id`
            )
        )
    LEFT JOIN `players` ON
        (
            `players`.`steam_id` = `verified_shifts`.`steam_id`
        )
    )
ORDER BY
    `sr1`.`timestamp`
DESC;
DROP TABLE IF EXISTS test_history;
CREATE VIEW test_history AS
SELECT
    `tests`.`id` AS `id`,
    `d`.`char_name` AS `student_name`,
    `tests`.`type` AS `type`,
    `tests`.`version` AS `version`,
    `tests`.`score_percent` AS `score_percent`,
    `tests`.`scores` AS `scores`,
    `e`.`char_name` AS `signed_by`,
    `f`.`label` AS `callsign`,
    `d`.`steam_id` AS `steam_id`,
    `tests`.`submit_date` AS `submit_date`
FROM
    (
        (
            (
                `tests`
            JOIN `players` `d`
            ON
                (
                    `tests`.`steam_id` = `d`.`steam_id`
                )
            )
        JOIN `players` `e`
        ON
            (
                `tests`.`signed_by` = `e`.`steam_id`
            )
        )
    LEFT JOIN `callsigns` `f`
    ON
        (
            `tests`.`signed_by` = `f`.`assigned_steam_id`
        )
    )
ORDER BY
    `tests`.`submit_date`
DESC;
DROP TABLE IF EXISTS unread_apps;
CREATE VIEW unread_apps AS
SELECT
    `applications_v0`.`app_id` AS `app_id`,
    `applications_v0`.`char_name` AS `char_name`,
    `applications_v0`.`discord_name` AS `discord_name`,
    `applications_v0`.`phone_number` AS `phone_number`,
    `applications_v0`.`app_timestamp` AS `app_timestamp`,
    `applications_v0`.`app_zoneOffset` AS `app_zoneOffset`
FROM
    `applications_v0`
WHERE
    `applications_v0`.`signed_by` IS NULL;

CREATE VIEW `_public_verified_shifts`  AS 
SELECT `_verified_shifts`.`id` AS `id`, 
`_verified_shifts`.`server` AS `server`, 
`_verified_shifts`.`steam_id` AS `steam_id`, 
`_verified_shifts`.`time_in` AS `time_in`, 
`_verified_shifts`.`time_out` AS `time_out`, 
`_verified_shifts`.`duration` AS `duration`, 
`_verified_shifts`.`timestamp` AS `timestamp`, 
`_verified_shifts`.`signed_by` AS `signed_by`, 
`callsigns`.`label` AS `callsign`, 
`players`.`char_name` AS `char_name`, 
`players`.`discord_name` AS `discord_name`, 
`players`.`rank` AS `rank` 
FROM (
    (
        `_verified_shifts` 
        left join `callsigns` 
        on(`callsigns`.`assigned_steam_id` = `_verified_shifts`.`steam_id`)
    )
    left join `players` 
    on(`players`.`steam_id` = `_verified_shifts`.`steam_id`)
) 
ORDER BY `_verified_shifts`.`time_in` DESC ;