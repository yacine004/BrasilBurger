package com.brasibturger.controllers;

import java.util.List;

import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.DeleteMapping;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.PutMapping;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RestController;

import com.brasibturger.dtos.CommandeDTO;
import com.brasibturger.services.CommandeService;

import jakarta.validation.Valid;
import lombok.RequiredArgsConstructor;

@RestController
@RequestMapping("/commandes")
@RequiredArgsConstructor
public class CommandeController {
    
    private final CommandeService commandeService;
    
    @GetMapping
    public ResponseEntity<List<CommandeDTO>> getAllCommandes() {
        return ResponseEntity.ok(commandeService.getAllCommandes());
    }
    
    @GetMapping("/{id}")
    public ResponseEntity<CommandeDTO> getCommandeById(@PathVariable Long id) {
        return ResponseEntity.ok(commandeService.getCommandeById(id));
    }
    
    @GetMapping("/client/{clientId}")
    public ResponseEntity<List<CommandeDTO>> getCommandesByClient(@PathVariable Long clientId) {
        return ResponseEntity.ok(commandeService.getCommandesByClient(clientId));
    }
    
    @GetMapping("/etat/{etat}")
    public ResponseEntity<List<CommandeDTO>> getCommandesByStatut(@PathVariable String etat) {
        return ResponseEntity.ok(commandeService.getCommandesByStatut(etat));
    }
    
    @GetMapping("/livraison/{typeLivraison}")
    public ResponseEntity<List<CommandeDTO>> getCommandesByTypeLivraison(@PathVariable String typeLivraison) {
        return ResponseEntity.ok(commandeService.getCommandesByTypeLivraison(typeLivraison));
    }
    
    @PostMapping
    public ResponseEntity<CommandeDTO> createCommande(@Valid @RequestBody CommandeDTO commandeDTO) {
        return ResponseEntity.status(HttpStatus.CREATED).body(commandeService.createCommande(commandeDTO));
    }
    
    @PutMapping("/{id}")
    public ResponseEntity<CommandeDTO> updateCommande(
            @PathVariable Long id,
            @Valid @RequestBody CommandeDTO commandeDTO) {
        return ResponseEntity.ok(commandeService.updateCommande(id, commandeDTO));
    }
    
    @DeleteMapping("/{id}")
    public ResponseEntity<Void> deleteCommande(@PathVariable Long id) {
        commandeService.deleteCommande(id);
        return ResponseEntity.noContent().build();
    }
}
