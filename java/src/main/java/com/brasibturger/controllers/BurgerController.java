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

import com.brasibturger.dtos.BurgerDTO;
import com.brasibturger.services.BurgerService;

import jakarta.validation.Valid;
import lombok.RequiredArgsConstructor;

@RestController
@RequestMapping("/burgers")
@RequiredArgsConstructor
public class BurgerController {
    
    private final BurgerService burgerService;
    
    @GetMapping
    public ResponseEntity<List<BurgerDTO>> getAllBurgers() {
        return ResponseEntity.ok(burgerService.getAllBurgers());
    }
    
    @GetMapping("/actifs")
    public ResponseEntity<List<BurgerDTO>> getActiveBurgers() {
        return ResponseEntity.ok(burgerService.getActiveBurgers());
    }
    
    @GetMapping("/{id}")
    public ResponseEntity<BurgerDTO> getBurgerById(@PathVariable Long id) {
        return ResponseEntity.ok(burgerService.getBurgerById(id));
    }
    
    @PostMapping
    public ResponseEntity<BurgerDTO> createBurger(@Valid @RequestBody BurgerDTO burgerDTO) {
        return ResponseEntity.status(HttpStatus.CREATED)
                .body(burgerService.createBurger(burgerDTO));
    }
    
    @PutMapping("/{id}")
    public ResponseEntity<BurgerDTO> updateBurger(
            @PathVariable Long id,
            @Valid @RequestBody BurgerDTO burgerDTO) {
        return ResponseEntity.ok(burgerService.updateBurger(id, burgerDTO));
    }
    
    @DeleteMapping("/{id}")
    public ResponseEntity<Void> deleteBurger(@PathVariable Long id) {
        burgerService.deleteBurger(id);
        return ResponseEntity.noContent().build();
    }
}
