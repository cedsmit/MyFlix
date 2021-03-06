<?php
/**
 * @param string $username
 * @param string $email
 * @param string $pass
 * @return array|null
 */
function register(string $username, string $email, string $pass): ?array
{
    global $lang;

    if (getUserByEmail($email))
        return ["email" => $lang["emailExists"]];

    $db = dbConnect();
    $hashedPass = hashPassword($pass);

    $stmt = mysqli_prepare($db, "INSERT INTO account (username, email, password) VALUES (?, ?, ?)");

    if (!mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashedPass) ||
        !mysqli_stmt_execute($stmt)) {
        die("Something went wrong with preparing the statement \n" . mysqli_error($db));
    }

    mysqli_stmt_close($stmt);
    dbClose($db);

    header("Location:login.php?registration=success");
    exit;
}

/**
 * If a user exists returns that user information
 * if a user does not exist returns false
 *
 * @param string $email
 * @return array|false
 */
function getUserByEmail(string $email): bool|array
{
    $db = dbConnect();
    $stmt = mysqli_prepare($db, "SELECT * from account where email = ?");

    if (!mysqli_stmt_bind_param($stmt, "s", $email) || !mysqli_stmt_execute($stmt)) {
        die("Something went wrong with preparing the statements \n" . mysqli_error($db));
    }

    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_free_result($stmt);
    mysqli_stmt_close($stmt);
    dbClose($db);

    if (mysqli_num_rows($result) === 0) {
        return false;
    }

    return mysqli_fetch_assoc($result);
}


function getCurrentUserId()
{
    if (!isset($_SESSION["userId"]))
        return false;

    return $_SESSION["userId"];
}

/**
 * @param int $userId
 * @return false|int
 */
function getUserAccountLevel(int $userId): bool|int
{
    $db = dbConnect();
    $stmt = mysqli_prepare($db, "SELECT accountLevel FROM account where id = ?");

    if (!mysqli_stmt_bind_param($stmt, "i", $userId) || !mysqli_stmt_execute($stmt)) {
        die("Something went wrong with preparing the statements \n" . mysqli_error($db));
    }

    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_free_result($stmt);
    mysqli_stmt_close($stmt);
    dbClose($db);

    if (mysqli_num_rows($result) === 0)
        return false;

    return mysqli_fetch_assoc($result)["accountLevel"];
}

/***
 * @param int $userId
 * @return bool|int
 */
function isUserVerified(int $userId): bool|int
{
    $db = dbConnect();
    $stmt = mysqli_prepare($db, "SELECT verified FROM account where id = ?");

    if (!mysqli_stmt_bind_param($stmt, "i", $userId) || !mysqli_stmt_execute($stmt)) {
        die("Something went wrong with preparing the statements \n" . mysqli_error($db));
    }

    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_free_result($stmt);
    mysqli_stmt_close($stmt);
    dbClose($db);

    if (mysqli_num_rows($result) === 0)
        return false;

    return mysqli_fetch_assoc($result)["verified"];
}

/**
 * @return bool returns user id if the user is logged in else returns false.
 */
function isUserLoggedIn(): int
{
    if (!isset($_SESSION["userId"]))
        return false;

    return true;
}

/**
 * hash password with the bcrypt algorithm
 *
 * @param string $password
 * @return string returns hashed password
 */
function hashPassword(string $password): string
{
    return password_hash($password, PASSWORD_BCRYPT);
}

function isValidIban(string $bankAccount): bool
{
    if (preg_match("/^[a-zA-Z]{2}[0-9]{2}[a-zA-Z0-9]{4}[0-9]{7}([a-zA-Z0-9]?){0,16}$/i", $bankAccount)) {
        return true;
    }
    return false;
}


function checkEmail(string $eMail, $accountId)
{
    $conn = dbConnect();
    $emailQuery = "SELECT * FROM account WHERE email = ? AND NOT id=?";

    if ($stmtEmail = mysqli_prepare($conn, $emailQuery)) {
        if (mysqli_stmt_bind_param($stmtEmail, "si", $eMail, $accountId)) {
            if (mysqli_stmt_execute($stmtEmail)) {
                mysqli_stmt_store_result($stmtEmail);

                if (mysqli_stmt_num_rows($stmtEmail) > 0) {
                    return false;
                } else {
                    if (filter_var($eMail, FILTER_VALIDATE_EMAIL)) {
                        return true;
                    } else {
                        return false;
                    }

                }
            }
        }
    }
}

function checkIban(string $bankAccount, $accountId)
{
    $conn = dbConnect();
    $ibanQuery = "SELECT * 
    FROM company 
    LEFT JOIN account on account.companyId = company.id
    WHERE company.iban = ? AND NOT account.id=?";

    if ($stmtIban = mysqli_prepare($conn, $ibanQuery)) {
        if (mysqli_stmt_bind_param($stmtIban, "si", $bankAccount, $accountId)) {
            if (mysqli_stmt_execute($stmtIban)) {
                mysqli_stmt_store_result($stmtIban);

                if (mysqli_stmt_num_rows($stmtIban) > 0) {
                    return false;
                } else {
                    if (isValidIban($bankAccount)) {
                        return true;
                    }else{
                        return false;
                    }
                }
            }
        }
    }
}