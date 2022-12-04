{ pkgs ? import <nixpkgs> {} }:
let
  php = pkgs.php81; 
in
pkgs.mkShell {
  nativeBuildInputs = [
    php
    php.packages.composer
  ] ++ (with pkgs; [
  ]);
}
